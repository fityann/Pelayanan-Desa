<?php

namespace App\Http\Controllers;

use App\Models\PencairanDana;
use Illuminate\Http\Request;

class PencairanDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = PencairanDana::with(['pemohon', 'apbdes']);
        
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        if ($request->filled('tahun')) {
            $query->tahun($request->tahun);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_permohonan', 'like', "%{$search}%")
                  ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('sumber_dana', 'like', "%{$search}%");
            });
        }
        
        $pencairan = $query->latest()->paginate(15)->withQueryString();
        
        $stats = [
            'total' => PencairanDana::count(),
            'total_dicairkan' => PencairanDana::where('status_pencairan', 'dicairkan')->sum('jumlah_pencairan'),
            'pending' => PencairanDana::whereIn('status_pencairan', ['draft', 'diajukan', 'diverifikasi'])->count(),
        ];
        
        return view('admin.pencairan-dana.index', compact('pencairan', 'stats'));
    }

    public function create()
    {
        return view('admin.pencairan-dana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'apbdes_id' => 'nullable|exists:apbdes,id',
            'nama_kegiatan' => 'required|string|max:500',
            'jumlah_pencairan' => 'required|numeric|min:0',
            'sumber_dana' => 'required|string',
            'jenis_pencairan' => 'required|string',
        ]);

        $data = $request->all();
        $data['nomor_permohonan'] = (new PencairanDana)->generateNomorPermohonan();
        $data['pemohon_id'] = auth()->id();
        $data['status_pencairan'] = 'draft';

        PencairanDana::create($data);

        return redirect()->route('admin.pencairan-dana.index')->with('success', 'Permohonan pencairan dana berhasil dibuat');
    }

    public function verify(Request $request, PencairanDana $pencairanDana)
    {
        $pencairanDana->update([
            'status_pencairan' => 'diverifikasi',
            'verifikator_keuangan_id' => auth()->id(),
            'tanggal_verifikasi' => now(),
        ]);

        return back()->with('success', 'Permohonan berhasil diverifikasi');
    }

    public function approve(Request $request, PencairanDana $pencairanDana)
    {
        $pencairanDana->update([
            'status_pencairan' => 'disetujui',
            'penandatangan_id' => auth()->id(),
            'tanggal_persetujuan' => now(),
        ]);

        return back()->with('success', 'Permohonan berhasil disetujui');
    }

    public function process(Request $request, PencairanDana $pencairanDana)
    {
        $request->validate([
            'metode_pembayaran' => 'nullable|string',
            'nama_bank' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'atas_nama' => 'nullable|string',
        ]);

        $pencairanDana->update([
            'status_pencairan' => 'diproses',
            'bendahara_id' => auth()->id(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        return back()->with('success', 'Pencairan sedang diproses');
    }

    public function complete(Request $request, PencairanDana $pencairanDana)
    {
        $request->validate([
            'catatan_pencairan' => 'nullable|string',
        ]);

        $pencairanDana->update([
            'status_pencairan' => 'dicairkan',
            'tanggal_pencairan' => now(),
            'catatan_pencairan' => $request->catatan_pencairan,
        ]);

        return back()->with('success', 'Dana berhasil dicairkan');
    }
}