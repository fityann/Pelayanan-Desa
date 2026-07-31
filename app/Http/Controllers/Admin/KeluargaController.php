<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeluargaController extends Controller
{
    public function index(): View
    {
        $keluargaList = Keluarga::withCount('penduduk')->latest()->paginate(15);
        return view('admin.keluarga.index', compact('keluargaList'));
    }

    public function create(): View
    {
        return view('admin.keluarga.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'no_kk' => ['required', 'string', 'size:16', 'unique:keluarga,no_kk'],
            'kepala_keluarga' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
        ]);

        Keluarga::create($request->all());

        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan');
    }

    public function edit(Keluarga $keluarga): View
    {
        return view('admin.keluarga.edit', compact('keluarga'));
    }

    public function update(Request $request, Keluarga $keluarga): RedirectResponse
    {
        $request->validate([
            'no_kk' => ['required', 'string', 'size:16', 'unique:keluarga,no_kk,' . $keluarga->id],
            'kepala_keluarga' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
        ]);

        $keluarga->update($request->all());

        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil diperbarui');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        $keluarga->delete();
        return redirect()->route('admin.keluarga.index')->with('success', 'Data keluarga berhasil dihapus');
    }
}
