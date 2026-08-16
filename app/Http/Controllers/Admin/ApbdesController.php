<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apbde;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApbdesController extends Controller
{
    public function index(): View
    {
        $tahunDipilih = request('tahun', date('Y'));
        $data = Apbde::where('tahun', $tahunDipilih)->get();
        $tahunList = Apbde::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $ringkasan = [
            'pendapatan' => $data->where('kategori', 'Pendapatan')->sum('anggaran'),
            'belanja' => $data->where('kategori', 'Belanja')->sum('anggaran'),
            'pembiayaan' => $data->where('kategori', 'Pembiayaan')->sum('anggaran'),
            'realisasi_pendapatan' => $data->where('kategori', 'Pendapatan')->sum('realisasi'),
            'realisasi_belanja' => $data->where('kategori', 'Belanja')->sum('realisasi'),
        ];

        $kategori = $data->groupBy('kategori');

        return view('admin.apbdes.index', compact('data', 'tahunDipilih', 'tahunList', 'ringkasan', 'kategori'));
    }

    public function create(): View
    {
        return view('admin.apbdes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => ['required', 'string', 'size:4'],
            'kategori' => ['required', 'in:Pendapatan,Belanja,Pembiayaan'],
            'bidang' => ['nullable', 'string', 'max:200'],
            'uraian' => ['required', 'string'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'realisasi' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';

        Apbde::create($data);

        // Respons JSON untuk submit via modal (AJAX)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data APBDes berhasil ditambahkan',
            ], 201);
        }

        return redirect()->route('admin.apbdes.index')->with('success', 'Data APBDes berhasil ditambahkan');
    }

    public function edit(Apbde $apbde): View
    {
        return view('admin.apbdes.edit', compact('apbde'));
    }

    public function update(Request $request, Apbde $apbde)
    {
        $request->validate([
            'tahun' => ['required', 'string', 'size:4'],
            'kategori' => ['required', 'in:Pendapatan,Belanja,Pembiayaan'],
            'bidang' => ['nullable', 'string', 'max:200'],
            'uraian' => ['required', 'string'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'realisasi' => ['nullable', 'numeric', 'min:0'],
        ]);

        $data = $request->all();
        $apbde->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data APBDes berhasil diperbarui',
            ], 200);
        }

        return redirect()->route('admin.apbdes.index')->with('success', 'Data APBDes berhasil diperbarui');
    }

    public function review(Apbde $apbde): RedirectResponse
    {
        $apbde->update([
            'status' => 'direview',
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->route('admin.apbdes.index')->with('success', 'APBDes sudah direview');
    }

    public function publish(Apbde $apbde): RedirectResponse
    {
        $apbde->update([
            'status' => 'dipublikasikan',
            'published_by' => auth()->id(),
            'tanggal_publikasi' => now(),
        ]);

        return redirect()->route('admin.apbdes.index')->with('success', 'APBDes berhasil dipublikasikan');
    }

    public function destroy(Apbde $apbde): RedirectResponse
    {
        $apbde->delete();
        return redirect()->route('admin.apbdes.index')->with('success', 'Data APBDes berhasil dihapus');
    }

    public function publik(): View
    {
        // Dukung konteks RT/RW lewat query (?rt=01&rw=01) agar navbar tetap scoped
        if (request()->has('rt') && request()->has('rw')) {
            session(['warga_rt' => request('rt'), 'warga_rw' => request('rw')]);
        }

        $tahun = request('tahun', date('Y'));
        $data = Apbde::where('tahun', $tahun)
            ->where('status', 'dipublikasikan')
            ->get();

        $tahunList = Apbde::where('status', 'dipublikasikan')
            ->select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $ringkasan = [
            'pendapatan' => $data->where('kategori', 'Pendapatan')->sum('anggaran'),
            'belanja' => $data->where('kategori', 'Belanja')->sum('anggaran'),
            'pembiayaan' => $data->where('kategori', 'Pembiayaan')->sum('anggaran'),
            'realisasi_pendapatan' => $data->where('kategori', 'Pendapatan')->sum('realisasi'),
            'realisasi_belanja' => $data->where('kategori', 'Belanja')->sum('realisasi'),
        ];

        $pendapatan = $data->where('kategori', 'Pendapatan');
        $belanja = $data->where('kategori', 'Belanja');

        return view('apbdes-publik', compact('data', 'tahun', 'tahunList', 'ringkasan', 'pendapatan', 'belanja'));
    }
}
