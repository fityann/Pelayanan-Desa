<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apbde;
use App\Models\Informasi;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\PengajuanSurat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Live Search API endpoint for Admin Header
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'results' => [],
                'total' => 0,
            ]);
        }

        $results = [];

        // 1. Cari Data Penduduk
        $penduduks = Penduduk::where('nama', 'like', "%{$q}%")
            ->orWhere('nik', 'like', "%{$q}%")
            ->orWhere('alamat', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($penduduks as $p) {
            $results[] = [
                'type' => 'Penduduk',
                'category' => 'Kependudukan',
                'title' => $p->nama,
                'subtitle' => "NIK: {$p->nik} — RT {$p->rt} / RW {$p->rw}",
                'icon' => 'person',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'url' => route('admin.penduduk.index', ['search' => $p->nik]),
            ];
        }

        // 2. Cari Pengajuan Surat
        $surats = PengajuanSurat::with(['user', 'jenisSurat'])
            ->where('kode_tracking', 'like', "%{$q}%")
            ->orWhere('keperluan', 'like', "%{$q}%")
            ->orWhereHas('user', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%");
            })
            ->orWhereHas('jenisSurat', function ($query) use ($q) {
                $query->where('nama_surat', 'like', "%{$q}%");
            })
            ->take(5)
            ->get();

        foreach ($surats as $s) {
            $pemohon = $s->user?->name ?? 'Warga';
            $jenis = $s->jenisSurat?->nama_surat ?? 'Surat';
            $results[] = [
                'type' => 'Surat',
                'category' => 'Layanan Surat',
                'title' => "[$s->kode_tracking] $jenis",
                'subtitle' => "Pemohon: $pemohon — Keperluan: " . Str::limit($s->keperluan ?? '-', 50),
                'icon' => 'description',
                'badge_color' => 'bg-teal-100 text-teal-800',
                'url' => route('admin.surat.pengajuan', ['search' => $s->kode_tracking]),
            ];
        }

        // 3. Cari Pengaduan Warga
        $pengaduans = Pengaduan::where('judul', 'like', "%{$q}%")
            ->orWhere('isi', 'like', "%{$q}%")
            ->orWhere('nama_pelapor', 'like', "%{$q}%")
            ->orWhere('nik_pelapor', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($pengaduans as $pd) {
            $results[] = [
                'type' => 'Pengaduan',
                'category' => 'Komunikasi',
                'title' => $pd->judul,
                'subtitle' => "Pelapor: " . ($pd->nama_pelapor ?? 'Warga') . " — Kategori: {$pd->kategori}",
                'icon' => 'campaign',
                'badge_color' => 'bg-amber-100 text-amber-800',
                'url' => route('admin.pengaduan.show', $pd->id),
            ];
        }

        // 4. Cari Informasi / Berita / Agenda
        $informasis = Informasi::where('judul', 'like', "%{$q}%")
            ->orWhere('ringkasan', 'like', "%{$q}%")
            ->orWhere('konten', 'like', "%{$q}%")
            ->take(4)
            ->get();

        foreach ($informasis as $inf) {
            $results[] = [
                'type' => 'Informasi',
                'category' => 'Berita & Agenda',
                'title' => $inf->judul,
                'subtitle' => "Kategori: " . ucfirst($inf->kategori) . " — " . Str::limit($inf->ringkasan ?? $inf->konten ?? '-', 50),
                'icon' => 'newspaper',
                'badge_color' => 'bg-blue-100 text-blue-800',
                'url' => route('admin.informasi.index', ['search' => $inf->judul]),
            ];
        }

        // 5. Cari APBDes / Keuangan
        $apbdes = Apbde::where('uraian', 'like', "%{$q}%")
            ->orWhere('kategori', 'like', "%{$q}%")
            ->orWhere('bidang', 'like', "%{$q}%")
            ->take(4)
            ->get();

        foreach ($apbdes as $apb) {
            $results[] = [
                'type' => 'APBDes',
                'category' => 'Keuangan',
                'title' => $apb->uraian,
                'subtitle' => "Anggaran: Rp " . number_format($apb->jumlah_anggaran ?? 0, 0, ',', '.') . " — Kategori: " . ucfirst($apb->kategori),
                'icon' => 'account_balance',
                'badge_color' => 'bg-cyan-100 text-cyan-800',
                'url' => route('admin.apbdes.index', ['search' => $apb->uraian]),
            ];
        }

        return response()->json([
            'results' => $results,
            'total' => count($results),
        ]);
    }
}
