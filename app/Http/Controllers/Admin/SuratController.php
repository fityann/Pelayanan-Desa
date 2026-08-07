<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function jenisSurat(): View
    {
        $jenisSurat = JenisSurat::all();
        return view('admin.surat.jenis', compact('jenisSurat'));
    }

    public function storeJenisSurat(Request $request): RedirectResponse
    {
        $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:jenis_surats,kode'],
            'nama' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
            'syarat' => ['nullable', 'string'],
            'masa_berlaku' => ['nullable', 'integer', 'min:1'],
            'butuh_ttd_fisik' => ['nullable', 'boolean'],
        ]);

        JenisSurat::create($request->all() + ['butuh_ttd_fisik' => $request->boolean('butuh_ttd_fisik', true)]);

        return redirect()->route('admin.surat.jenis')->with('success', 'Jenis surat berhasil ditambahkan');
    }

    public function pengajuanMasuk(): View
    {
        $pengajuan = PengajuanSurat::with(['user', 'jenisSurat'])
            ->whereNotIn('status', ['selesai'])
            ->latest()
            ->paginate(15);

        return view('admin.surat.pengajuan', compact('pengajuan'));
    }

    /**
     * Tahap 1: Admin Desa memverifikasi kelengkapan berkas.
     * diajukan -> diverifikasi_admin
     */
    public function verifikasi(PengajuanSurat $pengajuan): RedirectResponse
    {
        abort_unless($pengajuan->status === 'diajukan', 422);

        $pengajuan->update([
            'status' => 'diverifikasi_admin',
            'verified_by' => auth()->id(),
        ]);

        $pengajuan->catatStatus('diverifikasi_admin', 'Berkas lengkap, diteruskan ke Kepala Desa untuk approval.');

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan diverifikasi. Menunggu approval Kepala Desa.');
    }

    /**
     * Tahap 2 (hanya Kepala Desa): approval + generate nomor surat + draft PDF.
     * diverifikasi_admin -> disetujui_kades -> (menunggu_ttd_fisik | selesai)
     */
    public function approve(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        // Approval hanya oleh Kepala Desa (sesuai PRD Fase 1)
        abort_unless(auth()->user()->hasAnyRole(['Kepala Desa', 'Super Admin']), 403);
        abort_unless($pengajuan->status === 'diverifikasi_admin', 422);

        $nomor = $this->generateNomorSurat($pengajuan->jenisSurat->kode);

        $pengajuan->update([
            'status' => 'disetujui_kades',
            'nomor_surat' => $nomor,
            'approved_by' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        $pengajuan->catatStatus('disetujui_kades', "Surat disetujui. Nomor: {$nomor}");

        if ($pengajuan->butuh_ttd_fisik) {
            $pengajuan->update(['status' => 'menunggu_ttd_fisik']);
            $pengajuan->catatStatus('menunggu_ttd_fisik', 'Draft PDF siap cetak. Silakan cetak dan bawa ke Kepala Desa untuk tanda tangan.');
        } else {
            $pengajuan->update(['status' => 'selesai', 'tanggal_diambil' => now()]);
            $pengajuan->catatStatus('selesai', 'Surat tanpa TTD fisik, otomatis selesai. Warga dapat mengunduh PDF.');
        }

        return redirect()->route('admin.surat.pengajuan')->with('success', "Surat disetujui. Nomor: {$nomor}");
    }

    public function reject(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $request->validate(['alasan_ditolak' => ['required', 'string']]);

        abort_unless(in_array($pengajuan->status, ['diajukan', 'diverifikasi_admin']), 422);

        $pengajuan->update([
            'status' => 'ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);

        $pengajuan->catatStatus('ditolak', $request->alasan_ditolak);

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan ditolak');
    }

    /**
     * Tahap akhir: setelah tanda tangan fisik, admin update status selesai.
     * menunggu_ttd_fisik -> selesai
     */
    public function selesai(PengajuanSurat $pengajuan): RedirectResponse
    {
        abort_unless($pengajuan->status === 'menunggu_ttd_fisik', 422);

        $pengajuan->update([
            'status' => 'selesai',
            'tanggal_ttd_fisik' => now(),
            'tanggal_diambil' => now(),
        ]);

        $pengajuan->catatStatus('selesai', 'Surat telah ditandatangani dan siap diambil warga.');

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan selesai');
    }

    public function pdf(PengajuanSurat $pengajuan): \Illuminate\Http\Response
    {
        abort_unless(in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']), 422);

        $pdf = Pdf::loadView('pdf.surat', ['surat' => $pengajuan])
            ->setPaper('a4', 'portrait');

        $filename = (str_replace(['/', '\\'], '-', $pengajuan->nomor_surat) ?? 'draft')
            . '-' . str_replace([' ', '/', '\\'], '-', $pengajuan->pemohon_name) . '.pdf';

        return $pdf->download($filename);
    }

    public function arsip(): View
    {
        $arsip = PengajuanSurat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai'])
            ->latest()
            ->paginate(15);

        return view('admin.surat.arsip', compact('arsip'));
    }

    public function tracking(Request $request): View
    {
        $search = $request->get('search');
        $pengajuan = PengajuanSurat::with(['user', 'jenisSurat'])
            ->when($search, fn($q) => $q->where('nomor_surat', 'like', "%{$search}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15);

        return view('admin.surat.tracking', compact('pengajuan', 'search'));
    }

    private function generateNomorSurat(string $kode): string
    {
        $bulan = now()->format('m');
        $tahun = now()->format('Y');
        $count = PengajuanSurat::whereYear('created_at', $tahun)
            ->whereNotNull('nomor_surat')
            ->count() + 1;

        return sprintf('%s/%03d/%s/%s', $kode, $count, $bulan, $tahun);
    }
}
