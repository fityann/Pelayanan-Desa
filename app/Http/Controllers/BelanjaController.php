<?php

namespace App\Http\Controllers;

use App\Models\Belanja;
use Illuminate\Http\Request;

class BelanjaController extends Controller
{
    public function index(Request $request)
    {
        $query = Belanja::with(['pemohon', 'pencairanDana']);
        
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        if ($request->filled('jenis')) {
            $query->jenis($request->jenis);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_belanja', 'like', "%{$search}%")
                  ->orWhere('nama_barang_jasa', 'like', "%{$search}%")
                  ->orWhere('penyedia', 'like', "%{$search}%");
            });
        }
        
        $belanja = $query->latest()->paginate(15)->withQueryString();
        
        $stats = [
            'total' => Belanja::count(),
            'total_nilai' => Belanja::sum('total_harga'),
            'pending' => Belanja::whereIn('status_belanja', ['draft', 'diajukan'])->count(),
            'selesai' => Belanja::where('status_belanja', 'selesai')->count(),
        ];
        
        return view('admin.belanja.index', compact('belanja', 'stats'));
    }

    public function create()
    {
        return view('admin.belanja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pencairan_dana_id' => 'required|exists:pencairan_dana,id',
            'jenis_belanja' => 'required|string',
            'nama_barang_jasa' => 'required|string|max:500',
            'kuantitas' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:0',
            'metode_pengadaan' => 'required|string',
        ]);

        $data = $request->all();
        $data['nomor_belanja'] = (new Belanja)->generateNomorBelanja();
        $data['total_harga'] = $request->harga_satuan * $request->kuantitas;
        $data['pemohon_id'] = auth()->id();
        $data['status_belanja'] = 'draft';

        Belanja::create($data);

        return redirect()->route('admin.belanja.index')->with('success', 'Data belanja berhasil dibuat');
    }

    public function approve(Request $request, Belanja $belanja)
    {
        $belanja->update([
            'status_belanja' => 'diproses',
            'tanggal_persetujuan' => now(),
        ]);

        return back()->with('success', 'Belanja berhasil disetujui');
    }

    public function deliver(Request $request, Belanja $belanja)
    {
        $belanja->update([
            'status_belanja' => 'dikirim',
            'tanggal_pengiriman' => now(),
        ]);

        return back()->with('success', 'Barang ditandai sedang dikirim');
    }

    public function receive(Request $request, Belanja $belanja)
    {
        $request->validate([
            'catatan_penerimaan' => 'nullable|string',
        ]);

        $belanja->update([
            'status_belanja' => 'diterima',
            'penerima_id' => auth()->id(),
            'tanggal_penerimaan' => now(),
            'catatan_penerimaan' => $request->catatan_penerimaan,
        ]);

        return back()->with('success', 'Barang berhasil diterima');
    }

    public function complete(Request $request, Belanja $belanja)
    {
        $belanja->update([
            'status_belanja' => 'selesai',
        ]);

        return back()->with('success', 'Belanja ditandai selesai');
    }
}