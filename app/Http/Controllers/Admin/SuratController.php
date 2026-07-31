<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Models\User;
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
        ]);

        JenisSurat::create($request->all());

        return redirect()->route('admin.surat.jenis')->with('success', 'Jenis surat berhasil ditambahkan');
    }

    public function pengajuanMasuk(): View
    {
        $pengajuan = PengajuanSurat::with(['user', 'jenisSurat'])->latest()->paginate(15);
        return view('admin.surat.pengajuan', compact('pengajuan'));
    }

    public function verifikasi(PengajuanSurat $pengajuan): RedirectResponse
    {
        $pengajuan->update([
            'status' => 'diproses',
            'verified_by' => auth()->id(),
        ]);

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan diverifikasi');
    }

    public function approve(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $nomor = $this->generateNomorSurat($pengajuan->jenisSurat->kode);

        $pengajuan->update([
            'status' => 'disetujui',
            'nomor_surat' => $nomor,
            'approved_by' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        return redirect()->route('admin.surat.pengajuan')->with('success', "Surat disetujui. Nomor: {$nomor}");
    }

    public function reject(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $request->validate(['alasan_ditolak' => ['required', 'string']]);

        $pengajuan->update([
            'status' => 'ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan ditolak');
    }

    public function siapAmbil(PengajuanSurat $pengajuan): RedirectResponse
    {
        $pengajuan->update(['status' => 'siap_diambil']);

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Status: siap diambil');
    }

    public function selesai(PengajuanSurat $pengajuan): RedirectResponse
    {
        $pengajuan->update([
            'status' => 'selesai',
            'tanggal_diambil' => now(),
        ]);

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan selesai');
    }

    public function arsip(): View
    {
        $arsip = PengajuanSurat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['disetujui', 'siap_diambil', 'selesai'])
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
        $count = PengajuanSurat::whereYear('created_at', $tahun)->count() + 1;

        return sprintf('%s/%03d/%s/%s', $kode, $count, $bulan, $tahun);
    }
}
