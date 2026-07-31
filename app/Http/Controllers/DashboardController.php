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
            'diproses' => PengajuanSurat::where('status', 'diproses')->count(),
            'disetujui' => PengajuanSurat::where('status', 'disetujui')->count(),
            'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
            'siap_diambil' => PengajuanSurat::where('status', 'siap_diambil')->count(),
            'selesai' => PengajuanSurat::where('status', 'selesai')->count(),
        ];

        $totalAnggaran = Apbde::where('tahun', date('Y'))
            ->where('status', 'dipublikasikan')
            ->sum('anggaran');

        $totalRealisasi = Apbde::where('tahun', date('Y'))
            ->where('status', 'dipublikasikan')
            ->sum('realisasi');

        $pendapatan = Apbde::where('tahun', date('Y'))
            ->where('kategori', 'Pendapatan')
            ->where('status', 'dipublikasikan')
            ->sum('anggaran');

        $belanja = Apbde::where('tahun', date('Y'))
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
                'title' => "Surat {$p->jenisSurat->nama} - {$p->user->name}",
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
                'desc' => "Oleh: {$p->user->name}",
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
