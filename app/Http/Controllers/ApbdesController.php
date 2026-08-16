<?php

namespace App\Http\Controllers;

use App\Models\Apbde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApbdesController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $bidang = $request->get('bidang');
        
        // Ringkasan tahunan
        $summary = $this->getYearlySummary($tahun);
        
        // Perbandingan antar tahun
        $comparison = $this->getYearComparison();
        
        // Grafik serapan per bidang
        $absorptionChart = $this->getAbsorptionChart($tahun);
        
        // Breakdown sumber dana
        $sourceBreakdown = $this->getSourceBreakdown($tahun);
        
        // Data per bidang dengan filter
        $perBidang = $this->getPerBidang($tahun, $bidang);
        
        // Tahun-tahun yang tersedia
        $availableYears = Apbde::select(DB::raw('DISTINCT tahun'))
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
            
        return view('apbdes.index', compact(
            'summary',
            'comparison',
            'absorptionChart',
            'sourceBreakdown',
            'perBidang',
            'tahun',
            'bidang',
            'availableYears'
        ));
    }
    
    private function getYearlySummary($tahun)
    {
        return Apbde::select('kategori', DB::raw('SUM(anggaran) as total_anggaran'), DB::raw('SUM(realisasi) as total_realisasi'))
            ->where('tahun', $tahun)
            ->where('status', 'dipublikasikan')
            ->groupBy('kategori')
            ->get()
            ->mapWithKeys(function($item) {
                return [
                    $item->kategori => [
                        'anggaran' => (float) $item->total_anggaran,
                        'realisasi' => (float) $item->total_realisasi,
                        'persentase' => $item->total_anggaran > 0 
                            ? round(($item->total_realisasi / $item->total_anggaran) * 100, 2)
                            : 0
                    ]
                ];
            });
    }
    
    private function getYearComparison()
    {
        // MySQL tidak mendukung "LIMIT di dalam IN subquery",
        // jadi ambil 5 tahun terakhir sebagai array terlebih dahulu.
        $tahunTerakhir = Apbde::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->limit(5)
            ->pluck('tahun');

        return Apbde::select('tahun', 'kategori',
                DB::raw('SUM(anggaran) as total_anggaran'),
                DB::raw('SUM(realisasi) as total_realisasi'))
            ->whereIn('tahun', $tahunTerakhir)
            ->where('status', 'dipublikasikan')
            ->groupBy('tahun', 'kategori')
            ->orderBy('tahun', 'desc')
            ->get()
            ->groupBy('tahun')
            ->map(function($items) {
                return $items->mapWithKeys(function($item) {
                    return [
                        $item->kategori => [
                            'anggaran' => (float) $item->total_anggaran,
                            'realisasi' => (float) $item->total_realisasi
                        ]
                    ];
                });
            });
    }
    
    private function getAbsorptionChart($tahun)
    {
        return Apbde::select('bidang', 
                DB::raw('SUM(anggaran) as total_anggaran'), 
                DB::raw('SUM(realisasi) as total_realisasi'))
            ->where('tahun', $tahun)
            ->where('status', 'dipublikasikan')
            ->whereNotNull('bidang')
            ->groupBy('bidang')
            ->get()
            ->map(function($item) {
                $item->persentase = $item->total_anggaran > 0 
                    ? round(($item->total_realisasi / $item->total_anggaran) * 100, 2)
                    : 0;
                return $item;
            });
    }
    
    private function getSourceBreakdown($tahun)
    {
        return Apbde::select('sub_bidang as sumber_dana', 
                DB::raw('SUM(anggaran) as total_anggaran'), 
                DB::raw('SUM(realisasi) as total_realisasi'))
            ->where('tahun', $tahun)
            ->where('kategori', 'Pendapatan')
            ->where('status', 'dipublikasikan')
            ->whereNotNull('sub_bidang')
            ->groupBy('sub_bidang')
            ->get()
            ->map(function($item) {
                return [
                    'sumber_dana' => $item->sumber_dana,
                    'anggaran' => (float) $item->total_anggaran,
                    'realisasi' => (float) $item->total_realisasi,
                    'persentase' => $item->total_anggaran > 0 
                        ? round(($item->total_realisasi / $item->total_anggaran) * 100, 2)
                        : 0
                ];
            });
    }
    
    private function getPerBidang($tahun, $bidang = null)
    {
        $query = Apbde::where('tahun', $tahun)
            ->where('status', 'dipublikasikan');

        // Mode "rincian": filter bidang tertentu -> tampilkan detail per kegiatan
        if ($bidang) {
            return $query->where('bidang', $bidang)
                ->select('id', 'bidang', 'sub_bidang', 'uraian', 'anggaran', 'realisasi',
                    DB::raw('CASE WHEN anggaran > 0 THEN ROUND((realisasi / anggaran) * 100, 2) ELSE 0 END as persentase_realisasi'),
                    DB::raw('CASE 
                        WHEN (realisasi / anggaran) >= 0.9 THEN "selesai" 
                        WHEN (realisasi / anggaran) >= 0.5 THEN "proses" 
                        ELSE "belum" 
                    END as status_kegiatan'))
                ->orderBy('sub_bidang')
                ->orderBy('uraian')
                ->get()
                ->groupBy('bidang');
        }

        // Mode "ringkasan": tanpa filter -> agregate per bidang saja (aman only_full_group_by)
        return $query->whereNotNull('bidang')
            ->select('bidang',
                DB::raw('SUM(anggaran) as anggaran'),
                DB::raw('SUM(realisasi) as realisasi'),
                DB::raw('CASE WHEN SUM(anggaran) > 0 THEN ROUND((SUM(realisasi) / SUM(anggaran)) * 100, 2) ELSE 0 END as persentase_realisasi'),
                DB::raw('"ringkasan" as status_kegiatan'))
            ->groupBy('bidang')
            ->orderBy('bidang')
            ->get()
            ->map(function ($item) {
                // Bentuk ringkasan agar cocok dengan loop view (sub_bidang/uraian kosong)
                $item->sub_bidang = null;
                $item->uraian = $item->bidang;
                return $item;
            })
            ->groupBy('bidang');
    }
    
    public function show($id)
    {
        $apbdes = Apbde::with(['createdBy', 'reviewedBy', 'publishedBy'])
            ->findOrFail($id);
            
        // Related documents and activities
        // You might want to add relationships to your model
        
        return view('apbdes.show', compact('apbdes'));
    }
    
    public function export(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $data = $this->getYearlySummary($tahun);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('apbdes.pdf', compact('data', 'tahun'));
        
        return $pdf->download('Laporan_APBDes_' . $tahun . '.pdf');
    }
}