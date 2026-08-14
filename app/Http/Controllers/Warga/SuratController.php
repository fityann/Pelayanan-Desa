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
    public function indexRt(string $rt, string $rw = '01'): View
    {
        $rw = $rw ?: '01';
        $jenisSurat = JenisSurat::where('aktif', true)->get();

        return view('warga.surat.index-rt', compact('jenisSurat', 'rt', 'rw'));
    }

    /**
     * Form pembuatan surat dalam lingkar warga RT/RW (layout warga).
     */
    public function createRt(string $rt, JenisSurat $jenisSurat, string $rw = '01'): View
    {
        $rw = $rw ?: '01';
        abort_if(!$jenisSurat->aktif, 404);

        return view('warga.surat.create-rt', compact('jenisSurat', 'rt', 'rw'));
    }

    /**
     * Simpan pengajuan surat dari lingkar warga RT/RW.
     */
    public function storeRt(string $rt, Request $request, JenisSurat $jenisSurat, string $rw = '01'): RedirectResponse
    {
        $rw = $rw ?: '01';
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

        return redirect()->route('warga.rt.surat.status', ['rt' => $rt, 'kode' => $pengajuan->kode_tracking])
            ->with('success', 'Pengajuan surat berhasil dikirim. Simpan kode tracking Anda untuk memantau status.');
    }

    /**
     * Status pengajuan surat dalam lingkar warga RT/RW (layout warga).
     */
    public function statusRt(string $rt, string $kode, string $rw = '01'): View
    {
        $rw = $rw ?: '01';
        $cleanId = (int) str_replace('SRT-', '', $kode);
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'riwayatStatus.olehUser'])
            ->where('kode_tracking', $kode)
            ->when($cleanId > 0, fn($q) => $q->orWhere('id', $cleanId))
            ->firstOrFail();

        return view('warga.surat.status-rt', compact('pengajuan', 'rt', 'rw'));
    }

    /**
     * Halaman Riwayat & Tracking Surat Saya (layout warga).
     */
    public function riwayatRt(Request $request, string $rt, string $rw = '01'): View
    {
        $rw = $rw ?: '01';
        $wargaUser = auth('warga')->user();

        $query = PengajuanSurat::with(['jenisSurat', 'riwayatStatus']);

        // Filter pengajuan milik warga yang sedang login (by user_id atau NIK)
        if ($wargaUser) {
            $query->where(function($q) use ($wargaUser) {
                $q->where('user_id', $wargaUser->id);
                if ($wargaUser->nik) {
                    $q->orWhere('nik_pemohon', $wargaUser->nik);
                }
            });
        }

        // Search by tracking code / nama jenis surat / nomor surat
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('kode_tracking', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhereHas('jenisSurat', fn($j) => $j->where('nama', 'like', "%{$search}%"));
            });
        }

        // Filter by status tab
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pengajuanList = $query->latest()->paginate(10)->withQueryString();

        return view('warga.surat.riwayat-rt', compact('pengajuanList', 'rt', 'rw'));
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
            'data_isian' => ['nullable', 'array'],
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

        $wargaUser = auth('warga')->user();
        $nik = trim($request->nik);
        $nama = trim($request->nama);

        if (!$wargaUser || $wargaUser->hasAnyRole(['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'])) {
            if ($nik) {
                $wargaUser = \App\Models\User::where('nik', $nik)->first();
                if (!$wargaUser) {
                    $pd = \App\Models\Penduduk::where('nik', $nik)->first();
                    if ($pd) {
                        $wargaUser = \App\Models\User::firstOrCreate(
                            ['nik' => $pd->nik],
                            [
                                'name' => $pd->nama,
                                'email' => $pd->nik . '@puspamukti.local',
                                'password' => bcrypt('password'),
                                'rt' => $pd->rt,
                                'rw' => $pd->rw,
                            ]
                        );
                    }
                }
            }
        }

        $pengajuan = PengajuanSurat::create([
            'user_id' => $wargaUser?->id,
            'jenis_surat_id' => $jenisSurat->id,
            'status' => 'diajukan',
            'nama_pemohon' => $nama,
            'nik_pemohon' => $nik,
            'alamat_pemohon' => $request->alamat,
            'no_whatsapp' => $request->no_whatsapp,
            'kode_tracking' => $this->generateTracking(),
            'butuh_ttd_fisik' => $jenisSurat->butuh_ttd_fisik,
            'keterangan' => $request->keterangan,
            'data_isian' => $request->data_isian,
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

        // Kirim notifikasi bukti pengajuan ke Warga
        $userId = $user?->id ?? \App\Models\User::where('nik', $request->nik)->value('id');
        if ($userId) {
            Notification::buat($userId, [
                'judul' => 'Pengajuan Surat Terkirim 📬',
                'pesan' => "Pengajuan '{$jenisSurat->nama}' Anda telah berhasil dikirim dengan Kode Tracking: {$pengajuan->kode_tracking}.",
                'tipe' => 'surat',
                'icon' => 'mark_email_read',
                'warna' => 'bg-blue-100 text-blue-800',
                'link' => route('warga.surat.status', $pengajuan->kode_tracking),
            ]);
        }

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
        $cleanId = (int) str_replace('SRT-', '', $kode);
        $pengajuan = PengajuanSurat::with('jenisSurat')->where('kode_tracking', $kode)
            ->when($cleanId > 0, fn($q) => $q->orWhere('id', $cleanId))
            ->firstOrFail();

        abort_unless(in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']), 403);

        $viewName = view()->exists('pdf.surat_' . strtolower($pengajuan->jenisSurat->kode)) 
            ? 'pdf.surat_' . strtolower($pengajuan->jenisSurat->kode) 
            : 'pdf.surat';

        $pdf = Pdf::loadView($viewName, ['surat' => $pengajuan])
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
