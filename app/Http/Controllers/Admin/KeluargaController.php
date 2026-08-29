<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeluargaController extends Controller
{
    public function index(Request $request): View
    {
        // Query base dengan eager loading
        $query = Keluarga::withCount('penduduk');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                  ->orWhere('kepala_keluarga', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }
        
        if ($request->filled('min_anggota')) {
            $query->has('penduduk', '>=', $request->min_anggota);
        }
        
        if ($request->filled('max_anggota')) {
            $query->has('penduduk', '<=', $request->max_anggota);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['no_kk', 'kepala_keluarga', 'rt', 'rw', 'created_at', 'penduduk_count'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        // Get RT/RW list for filter dropdown
        $rtList = Keluarga::select('rt')->distinct()->whereNotNull('rt')->orderBy('rt')->pluck('rt');
        $rwList = Keluarga::select('rw')->distinct()->whereNotNull('rw')->orderBy('rw')->pluck('rw');
        
        $keluargaList = $query->paginate(15)->withQueryString();
        
        return view('admin.keluarga.index', compact('keluargaList', 'rtList', 'rwList'));
    }

    public function create(): View
    {
        return view('admin.keluarga.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if (!empty($input['rt'])) {
            $input['rt'] = sprintf('%02d', (int)$input['rt']);
        }
        if (!empty($input['rw'])) {
            $input['rw'] = sprintf('%02d', (int)$input['rw']);
        }
        if (empty($input['desa']) || trim($input['desa']) === '') {
            $input['desa'] = 'Puspamukti';
        }
        if (empty($input['kecamatan']) || trim($input['kecamatan']) === '') {
            $input['kecamatan'] = 'Cigalontang';
        }
        if (empty($input['kepala_keluarga']) || trim($input['kepala_keluarga']) === '') {
            $input['kepala_keluarga'] = 'Belum Ditetapkan';
        }
        $request->merge($input);

        $request->validate([
            'no_kk' => ['required', 'string', 'size:16', 'unique:keluarga,no_kk'],
            'kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
        ]);

        Keluarga::create($request->only([
            'no_kk', 'kepala_keluarga', 'alamat', 'rt', 'rw',
            'desa', 'kecamatan', 'kabupaten', 'provinsi',
        ]));

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Data keluarga berhasil ditambahkan'], 201);
        }

        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan');
    }

    public function show(Keluarga $keluarga): View
    {
        $keluarga->load('penduduk');
        return view('admin.keluarga.show', compact('keluarga'));
    }

    public function edit(Keluarga $keluarga): View
    {
        return view('admin.keluarga.edit', compact('keluarga'));
    }

    public function update(Request $request, Keluarga $keluarga)
    {
        $input = $request->all();
        if (!empty($input['rt'])) {
            $input['rt'] = sprintf('%02d', (int)$input['rt']);
        }
        if (!empty($input['rw'])) {
            $input['rw'] = sprintf('%02d', (int)$input['rw']);
        }
        if (empty($input['desa']) || trim($input['desa']) === '') {
            $input['desa'] = 'Puspamukti';
        }
        if (empty($input['kecamatan']) || trim($input['kecamatan']) === '') {
            $input['kecamatan'] = 'Cigalontang';
        }
        if (empty($input['kepala_keluarga']) || trim($input['kepala_keluarga']) === '') {
            $input['kepala_keluarga'] = 'Belum Ditetapkan';
        }
        $request->merge($input);

        $request->validate([
            'no_kk' => ['required', 'string', 'size:16', 'unique:keluarga,no_kk,' . $keluarga->id],
            'kepala_keluarga' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
        ]);

        $keluarga->update($request->only([
            'no_kk', 'kepala_keluarga', 'alamat', 'rt', 'rw',
            'desa', 'kecamatan', 'kabupaten', 'provinsi',
        ]));

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Data keluarga berhasil diperbarui'], 200);
        }

        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil diperbarui');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        $keluarga->delete();
        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil dihapus');
    }
}
