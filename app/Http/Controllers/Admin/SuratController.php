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

    public function pengajuanMasuk(Request $request): View
    {
        $query = PengajuanSurat::with(['user', 'jenisSurat']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_tracking', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                  })
                  ->orWhereHas('jenisSurat', function($j) use ($search) {
                      $j->where('nama_surat', 'like', "%{$search}%");
                  });
            });
        } else {
            $query->whereNotIn('status', ['selesai']);
        }

        $pengajuan = $query->latest()->paginate(15)->withQueryString();

        return view('admin.surat.pengajuan', compact('pengajuan'));
    }

    /**
     * Tahap 1: Admin Desa memverifikasi kelengkapan berkas.
     * diajukan -> diverifikasi_admin
     */
    public function verifikasi(PengajuanSurat $pengajuan): RedirectResponse
    {
        abort_unless($pengajuan->status === 'diajukan', 422);
        $pengajuan->loadMissing('jenisSurat');

        $pengajuan->update([
            'status' => 'diverifikasi_admin',
            'verified_by' => auth()->id(),
        ]);

        $pengajuan->catatStatus('diverifikasi_admin', 'Berkas lengkap, diteruskan ke Kepala Desa untuk approval.');

        // Kirim Notifikasi ke Warga
        $userId = $this->resolveCitizenUserId($pengajuan);
        if ($userId) {
            \App\Models\Notification::buat($userId, [
                'judul' => 'Pengajuan Surat Diverifikasi',
                'pesan' => "Pengajuan Surat '{$pengajuan->jenisSurat->nama}' Anda telah diverifikasi oleh Admin Desa dan diteruskan ke Kepala Desa.",
                'tipe' => 'surat',
                'icon' => 'verified',
                'warna' => 'bg-amber-100 text-amber-800',
                'link' => route('warga.surat.status', $pengajuan->kode_tracking ?? $pengajuan->id),
            ]);
        }

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

        $pengajuan->loadMissing('jenisSurat');
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

        // Kirim Notifikasi ke Warga bahwa Surat Disetujui
        $userId = $this->resolveCitizenUserId($pengajuan);
        if ($userId) {
            \App\Models\Notification::buat($userId, [
                'judul' => 'Pengajuan Surat Disetujui! 🎉',
                'pesan' => "Selamat! Surat '{$pengajuan->jenisSurat->nama}' Anda telah disetujui Kepala Desa dengan Nomor Surat: {$nomor}.",
                'tipe' => 'surat',
                'icon' => 'check_circle',
                'warna' => 'bg-emerald-100 text-emerald-800',
                'link' => route('warga.surat.status', $pengajuan->kode_tracking ?? $pengajuan->id),
            ]);
        }

        return redirect()->route('admin.surat.pengajuan')->with('success', "Surat disetujui. Nomor: {$nomor}");
    }

    public function reject(Request $request, PengajuanSurat $pengajuan): RedirectResponse
    {
        $request->validate(['alasan_ditolak' => ['required', 'string']]);

        abort_unless(in_array($pengajuan->status, ['diajukan', 'diverifikasi_admin']), 422);
        $pengajuan->loadMissing('jenisSurat');

        $pengajuan->update([
            'status' => 'ditolak',
            'alasan_ditolak' => $request->alasan_ditolak,
        ]);

        $pengajuan->catatStatus('ditolak', $request->alasan_ditolak);

        // Kirim Notifikasi Penolakan ke Warga
        $userId = $this->resolveCitizenUserId($pengajuan);
        if ($userId) {
            \App\Models\Notification::buat($userId, [
                'judul' => 'Pengajuan Surat Ditolak',
                'pesan' => "Pengajuan Surat '{$pengajuan->jenisSurat->nama}' ditolak. Alasan: {$request->alasan_ditolak}",
                'tipe' => 'surat',
                'icon' => 'cancel',
                'warna' => 'bg-rose-100 text-rose-800',
                'link' => route('warga.surat.status', $pengajuan->kode_tracking ?? $pengajuan->id),
            ]);
        }

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan ditolak');
    }

    /**
     * Tahap akhir: setelah tanda tangan fisik, admin update status selesai.
     * menunggu_ttd_fisik -> selesai
     */
    public function selesai(PengajuanSurat $pengajuan): RedirectResponse
    {
        abort_unless($pengajuan->status === 'menunggu_ttd_fisik', 422);
        $pengajuan->loadMissing('jenisSurat');

        $pengajuan->update([
            'status' => 'selesai',
            'tanggal_ttd_fisik' => now(),
            'tanggal_diambil' => now(),
        ]);

        $pengajuan->catatStatus('selesai', 'Surat telah ditandatangani dan siap diambil warga.');

        // Kirim Notifikasi Selesai ke Warga
        $userId = $this->resolveCitizenUserId($pengajuan);
        if ($userId) {
            \App\Models\Notification::buat($userId, [
                'judul' => 'Surat Selesai & Siap Diambil 📜',
                'pesan' => "Surat '{$pengajuan->jenisSurat->nama}' (No: {$pengajuan->nomor_surat}) telah selesai ditandatangani Kepala Desa dan siap diambil di Kantor Desa.",
                'tipe' => 'surat',
                'icon' => 'task_alt',
                'warna' => 'bg-emerald-100 text-emerald-800',
                'link' => route('warga.surat.status', $pengajuan->kode_tracking ?? $pengajuan->id),
            ]);
        }

        return redirect()->route('admin.surat.pengajuan')->with('success', 'Pengajuan selesai');
    }

    /**
     * Mencari ID User Warga pemohon secara presisi untuk pengiriman notifikasi.
     */
    private function resolveCitizenUserId(PengajuanSurat $pengajuan): ?int
    {
        // 1. Cek NIK Pemohon (Prioritas tertinggi & paling akurat)
        $nik = trim($pengajuan->nik_pemohon ?? '');
        if (!empty($nik)) {
            $user = \App\Models\User::where('nik', $nik)->first();
            if ($user) {
                return $user->id;
            }

            // Jika ada data Penduduk KTP desa, sinkronkan atau buat akun user warga
            $pd = \App\Models\Penduduk::where('nik', $nik)->first();
            if ($pd) {
                $user = \App\Models\User::firstOrCreate(
                    ['nik' => $pd->nik],
                    [
                        'name' => $pd->nama,
                        'email' => $pd->nik . '@puspamukti.local',
                        'password' => bcrypt('password'),
                        'rt' => $pd->rt,
                        'rw' => $pd->rw,
                    ]
                );
                return $user->id;
            }
        }

        // 2. Cek user_id milik non-admin/perangkat desa
        if ($pengajuan->user_id) {
            $u = \App\Models\User::find($pengajuan->user_id);
            if ($u && !$u->hasAnyRole(['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'])) {
                return $u->id;
            }
        }

        // 3. Cek Nama Pemohon
        $nama = trim($pengajuan->pemohon_name ?? '');
        if (!empty($nama)) {
            $user = \App\Models\User::where('name', 'like', "%{$nama}%")->first();
            if ($user) {
                return $user->id;
            }
        }

        return null;
    }

    public function pdf(PengajuanSurat $pengajuan): \Illuminate\Http\Response
    {
        abort_unless(in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']), 422);
        
        $pengajuan->loadMissing('jenisSurat');
        
        $viewName = view()->exists('pdf.surat_' . strtolower($pengajuan->jenisSurat->kode)) 
            ? 'pdf.surat_' . strtolower($pengajuan->jenisSurat->kode) 
            : 'pdf.surat';

        $pdf = Pdf::loadView($viewName, ['surat' => $pengajuan])
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

    public function exportArsip(Request $request)
    {
        $query = PengajuanSurat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai'])
            ->latest();

        $arsipList = $query->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Arsip Surat');

        // Header
        $headers = [
            'A1' => 'No', 'B1' => 'Nomor Surat', 'C1' => 'Jenis Surat', 
            'D1' => 'Nama Pemohon', 'E1' => 'NIK Pemohon', 'F1' => 'Tanggal Pengajuan', 
            'G1' => 'Status'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // Data
        $row = 2;
        foreach ($arsipList as $index => $s) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $s->nomor_surat ?? '-');
            $sheet->setCellValue('C' . $row, $s->jenisSurat->nama ?? '-');
            $sheet->setCellValue('D' . $row, $s->pemohon_name);
            $sheet->setCellValueExplicit('E' . $row, $s->nik_pemohon, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $row, $s->created_at->format('Y-m-d H:i'));
            $sheet->setCellValue('G' . $row, ucfirst(str_replace('_', ' ', $s->status)));
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Arsip_Surat_' . date('Ymd_His') . '.xlsx';
        
        // Output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
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
