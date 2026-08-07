<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = $request->only(['status', 'kategori', 'sumber', 'start_date', 'end_date', 'rt', 'rw']);
        
        // Base query
        $query = Pengaduan::with(['user', 'processedBy'])
            ->orderBy('tanggal_diterima', 'desc');
        
        // Apply filters
        if (!empty($filters['status'])) {
            $query->whereIn('status', (array)$filters['status']);
        } else {
            $query->whereIn('status', ['diterima', 'diproses']);
        }
        
        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }
        
        if (!empty($filters['sumber'])) {
            $query->whereIn('sumber_akses', (array)$filters['sumber']);
        }
        
        if (!empty($filters['start_date'])) {
            $query->whereDate('tanggal_diterima', '>=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query->whereDate('tanggal_diterima', '<=', $filters['end_date']);
        }
        
        if (!empty($filters['rt'])) {
            $query->where('rt', $filters['rt']);
        }
        
        if (!empty($filters['rw'])) {
            $query->where('rw', $filters['rw']);
        }
        
        // Paginate results
        $pengaduans = $query->paginate(15)->withQueryString();
        
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get chart data
        $chartData = $this->getChartData();
        
        // Category list for filter
        $kategories = [
            'sampah' => 'Sampah Menumpuk',
            'jalan' => 'Kerusakan Jalan',
            'drainase' => 'Drainase Tersumbat',
            'penerangan' => 'Lampu Jalan Rusak',
            'air' => 'Masalah Air Bersih',
            'lainnya' => 'Lainnya'
        ];
        
        return view('admin.pengaduan.dashboard', compact('pengaduans', 'stats', 'chartData', 'kategories'));
    }
    
    private function getStatistics()
    {
        $total = Pengaduan::count();
        $today = Pengaduan::whereDate('tanggal_diterima', today())->count();
        
        $diterima = Pengaduan::where('status', 'diterima')->count();
        $diproses = Pengaduan::where('status', 'diproses')->count();
        $selesai = Pengaduan::where('status', 'selesai')->count();
        
        return [
            'total' => $total,
            'today' => $today,
            'diterima' => $diterima,
            'diproses' => $diproses,
            'selesai' => $selesai,
            'diterima_percent' => $total > 0 ? round(($diterima / $total) * 100, 1) : 0,
            'diproses_percent' => $total > 0 ? round(($diproses / $total) * 100, 1) : 0,
            'selesai_percent' => $total > 0 ? round(($selesai / $total) * 100, 1) : 0,
        ];
    }
    
    private function getChartData()
    {
        // Category data
        $categories = Pengaduan::select('kategori', DB::raw('COUNT(*) as count'))
            ->groupBy('kategori')
            ->get();
        
        $categoryLabels = $categories->map(function($item) {
            $map = [
                'sampah' => 'Sampah',
                'jalan' => 'Jalan',
                'drainase' => 'Drainase',
                'penerangan' => 'Penerangan',
                'air' => 'Air',
                'lainnya' => 'Lainnya'
            ];
            return $map[$item->kategori] ?? $item->kategori;
        })->toArray();
        
        $categoryData = $categories->pluck('count')->toArray();
        
        // Trend data (last 7 days)
        $trendLabels = [];
        $trendDiterima = [];
        $trendDiproses = [];
        $trendSelesai = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trendLabels[] = $date->format('d/m');
            
            $trendDiterima[] = Pengaduan::whereDate('tanggal_diterima', $date)
                ->where('status', 'diterima')
                ->count();
                
            $trendDiproses[] = Pengaduan::whereDate('tanggal_diterima', $date)
                ->where('status', 'diproses')
                ->count();
                
            $trendSelesai[] = Pengaduan::whereDate('tanggal_diterima', $date)
                ->where('status', 'selesai')
                ->count();
        }
        
        return [
            'categoryLabels' => $categoryLabels,
            'categoryData' => $categoryData,
            'trendLabels' => $trendLabels,
            'trendDiterima' => $trendDiterima,
            'trendDiproses' => $trendDiproses,
            'trendSelesai' => $trendSelesai,
        ];
    }
    
    public function show($id)
    {
        $pengaduan = Pengaduan::with(['user', 'processedBy'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $pengaduan,
            'html' => view('admin.pengaduan.partials.detail', compact('pengaduan'))->render()
        ]);
    }

    /**
     * Pengaduan masuk dari warga (QR/portal) — admin tidak perlu membuat manual.
     * Method ini hanya untuk menjaga route legacy agar tidak 500.
     */
    public function create()
    {
        return redirect()->route('admin.pengaduan.index')
            ->with('info', 'Pengaduan dibuat otomatis oleh warga melalui QR Code / portal.');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.pengaduan.index')
            ->with('info', 'Pengaduan dibuat otomatis oleh warga melalui QR Code / portal.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
    
    public function proses($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        
        if ($pengaduan->status !== 'diterima') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengaduan dengan status "diterima" yang bisa diproses'
            ], 400);
        }
        
        $pengaduan->status = 'diproses';
        $pengaduan->processed_by = auth()->id();
        $pengaduan->tanggal_diproses = now();
        $pengaduan->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diproses'
        ]);
    }
    
    public function selesai(Request $request, $id)
    {
        $request->validate([
            'tanggapan' => 'nullable|string'
        ]);
        
        $pengaduan = Pengaduan::findOrFail($id);
        
        if ($pengaduan->status !== 'diproses') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengaduan dengan status "diproses" yang bisa diselesaikan'
            ], 400);
        }
        
        $pengaduan->status = 'selesai';
        $pengaduan->tanggapan = $request->tanggapan;
        $pengaduan->tanggal_selesai = now();
        $pengaduan->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diselesaikan'
        ]);
    }
    
    public function export(Request $request)
    {
        // This is a placeholder for export functionality
        // You can implement CSV or Excel export here
        
        return response()->json([
            'message' => 'Export functionality to be implemented',
            'filters' => $request->all()
        ]);
    }
}