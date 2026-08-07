<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\Notification;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function index(): View
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();

        return view('warga.surat.index', compact('jenisSurat'));
    }

    public function create(JenisSurat $jenisSurat): View
    {
        abort_if(!$jenisSurat->aktif, 404);

        return view('warga.surat.create', compact('jenisSurat'));
    }

    /**
     * Daftar jenis surat dalam lingkar warga RT/RW (layout warga).
     */
    public function indexRt(string $rt, string $rw): View
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();

        return view('warga.surat.index-rt', compact('jenisSurat', 'rt', 'rw'));
    }

    /**
     * Form pembuatan surat dalam lingkar warga RT/RW (layout warga).
     */
    public function createRt(string $rt, string $rw, JenisSurat $jenisSurat): View
    {
        abort_if(!$jenisSurat->aktif, 404);

        return view('warga.surat.create-rt', compact('jenisSurat', 'rt', 'rw'));
    }

    /**
     * Simpan pengajuan surat dari lingkar warga RT/RW.
     */
    public function storeRt(string $rt, string $rw, Request $request, JenisSurat $jenisSurat): RedirectResponse
    {
        abort_if(!$jenisSurat->aktif, 404);

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'digits:16'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'file_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $pengajuan = $this->buatPengajuan($request, $jenisSurat);

        return redirect()->route('warga.rt.surat.status', ['rt' => $rt, 'rw' => $rw, 'kode' => $pengajuan->kode_tracking])
            ->with('success', 'Pengajuan surat berhasil dikirim. Simpan kode tracking Anda untuk memantau status.');
    }

    /**
     * Status pengajuan surat dalam lingkar warga RT/RW (layout warga).
     */
    public function statusRt(string $rt, string $rw, string $kode): View
    {
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'riwayatStatus.olehUser'])
            ->where('kode_tracking', $kode)
            ->firstOrFail();

        return view('warga.surat.status-rt', compact('pengajuan', 'rt', 'rw'));
    }

    public function store(Request $request, JenisSurat $jenisSurat): RedirectResponse
    {
        abort_if(!$jenisSurat->aktif, 404);

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'digits:16'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'file_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $pengajuan = $this->buatPengajuan($request, $jenisSurat);

        return redirect()->route('warga.surat.status', $pengajuan->kode_tracking)
            ->with('success', 'Pengajuan surat berhasil dikirim. Simpan kode tracking Anda untuk memantau status.');
    }

    /**
     * Logika pembuatan pengajuan surat (dipakai store & storeRt).
     */
    private function buatPengajuan(Request $request, JenisSurat $jenisSurat): PengajuanSurat
    {
        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('pengajuan-surat', 'public');
        }

        $user = $request->user();

        $pengajuan = PengajuanSurat::create([
            'user_id' => $user?->id,
            'jenis_surat_id' => $jenisSurat->id,
            'status' => 'diajukan',
            'nama_pemohon' => $request->nama,
            'nik_pemohon' => $request->nik,
            'alamat_pemohon' => $request->alamat,
            'no_whatsapp' => $request->no_whatsapp,
            'kode_tracking' => $this->generateTracking(),
            'butuh_ttd_fisik' => $jenisSurat->butuh_ttd_fisik,
            'keterangan' => $request->keterangan,
            'file_pendukung' => $filePath,
            'tanggal_diajukan' => now(),
        ]);

        $pengajuan->catatStatus('diajukan', 'Pengajuan diterima sistem. Menunggu verifikasi Admin Desa.');

        // Kirim notifikasi ke semua staff/admin
        Notification::kirimKeStaff([
            'judul' => 'Pengajuan surat baru: ' . $jenisSurat->nama,
            'pesan' => $request->nama . ' mengajukan ' . $jenisSurat->nama . ' (kode: ' . $pengajuan->kode_tracking . ')',
            'tipe' => 'surat',
            'icon' => 'edit_note',
            'warna' => 'bg-primary/10 text-primary',
            'link' => route('admin.surat.pengajuan'),
        ]);

        return $pengajuan;
    }

    public function cek(): View
    {
        $kode = request('kode');

        $pengajuan = $kode
            ? PengajuanSurat::with('jenisSurat')->where('kode_tracking', trim($kode))->first()
            : null;

        return view('warga.surat.cek', compact('kode', 'pengajuan'));
    }

    public function status(string $kode): View
    {
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'riwayatStatus.olehUser'])
            ->where('kode_tracking', $kode)
            ->firstOrFail();

        return view('warga.surat.status', compact('pengajuan'));
    }

    public function pdf(string $kode): \Illuminate\Http\Response
    {
        $pengajuan = PengajuanSurat::where('kode_tracking', $kode)->firstOrFail();

        abort_unless(in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']), 403);

        $pdf = Pdf::loadView('pdf.surat', ['surat' => $pengajuan])
            ->setPaper('a4', 'portrait');

        $filename = (str_replace(['/', '\\'], '-', $pengajuan->nomor_surat) ?? 'draft')
            . '-' . str_replace([' ', '/', '\\'], '-', $pengajuan->pemohon_name) . '.pdf';

        return $pdf->download($filename);
    }

    private function generateTracking(): string
    {
        do {
            $kode = 'SRT-' . date('dmY') . '-' . strtoupper(Str::random(4));
        } while (PengajuanSurat::where('kode_tracking', $kode)->exists());

        return $kode;
    }
}
