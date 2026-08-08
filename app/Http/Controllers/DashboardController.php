<?php

namespace App\Http\Controllers;

use App\Models\Apbde;
use App\Models\Informasi;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSurat = PengajuanSurat::count();
        $suratBulanIni = PengajuanSurat::whereMonth('created_at', now()->month)->count();
        $pengaduanAktif = Pengaduan::whereIn('status', ['diterima', 'diproses'])->count();
        $wargaTerdaftar = User::count();

        $suratPerStatus = [
            'diajukan' => PengajuanSurat::where('status', 'diajukan')->count(),
            'diverifikasi_admin' => PengajuanSurat::where('status', 'diverifikasi_admin')->count(),
            'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
            'disetujui_kades' => PengajuanSurat::where('status', 'disetujui_kades')->count(),
            'menunggu_ttd_fisik' => PengajuanSurat::where('status', 'menunggu_ttd_fisik')->count(),
            'selesai' => PengajuanSurat::where('status', 'selesai')->count(),
        ];

        $latestTahun = Apbde::where('status', 'dipublikasikan')->max('tahun') ?? date('Y');

        $totalAnggaran = Apbde::where('tahun', $latestTahun)
            ->where('status', 'dipublikasikan')
            ->sum('anggaran');

        $totalRealisasi = Apbde::where('tahun', $latestTahun)
            ->where('status', 'dipublikasikan')
            ->sum('realisasi');

        $pendapatan = Apbde::where('tahun', $latestTahun)
            ->where('kategori', 'Pendapatan')
            ->where('status', 'dipublikasikan')
            ->sum('anggaran');

        $belanja = Apbde::where('tahun', $latestTahun)
            ->where('kategori', 'Belanja')
            ->where('status', 'dipublikasikan')
            ->sum('anggaran');

        $pengaduanPerKategori = Pengaduan::selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->get();

        $agendaTerdekat = Informasi::where('published', true)
            ->where('kategori', 'agenda')
            ->where('tanggal_kegiatan', '>=', now())
            ->orderBy('tanggal_kegiatan')
            ->take(5)
            ->get();

        $aktivitasTerbaru = collect();

        $pengajuanTerbaru = PengajuanSurat::with(['user', 'jenisSurat'])
            ->latest()->take(3)->get()
            ->map(fn($p) => [
                'icon' => 'verified',
                'icon_bg' => 'bg-primary/10',
                'icon_color' => 'text-primary',
                'title' => "Surat {$p->jenisSurat->nama} - {$p->pemohonName()}",
                'desc' => "Status: " . ucfirst($p->status),
                'time' => $p->created_at->diffForHumans(),
                'badge' => ucfirst($p->status),
                'badge_class' => $p->status === 'disetujui' ? 'bg-success/10 text-success' : ($p->status === 'ditolak' ? 'bg-error/10 text-error' : 'bg-on-tertiary-container/10 text-on-tertiary-container'),
            ]);

        $pengaduanTerbaru = Pengaduan::with('user')->latest()->take(3)->get()
            ->map(fn($p) => [
                'icon' => 'campaign',
                'icon_bg' => 'bg-error/10',
                'icon_color' => 'text-error',
                'title' => "Pengaduan: {$p->judul}",
                'desc' => "Oleh: {$p->nama_pelapor}",
                'time' => $p->created_at->diffForHumans(),
                'badge' => ucfirst($p->status),
                'badge_class' => $p->status === 'selesai' ? 'bg-success/10 text-success' : 'bg-error/10 text-error',
            ]);

        $aktivitasTerbaru = $pengajuanTerbaru->concat($pengaduanTerbaru)->sortByDesc('time')->take(5);

        $realisasiPersen = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100) : 0;

        return view('dashboard', compact(
            'totalSurat', 'suratBulanIni', 'pengaduanAktif', 'wargaTerdaftar',
            'suratPerStatus', 'totalAnggaran', 'totalRealisasi',
            'pendapatan', 'belanja', 'realisasiPersen',
            'pengaduanPerKategori', 'agendaTerdekat', 'aktivitasTerbaru'
        ));
    }
}
