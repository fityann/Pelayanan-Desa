<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class KategoriAsetController extends Controller
{
    public function index()
    {
        $kategoriAsets = KategoriAset::withCount('asets')->orderBy('name')->get();
        return view('admin.kategori-aset.index', compact('kategoriAsets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name',
        ]);

        KategoriAset::create($request->only('name'));

        return back()->with('success', 'Kategori aset berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriAset $kategori_aset)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $kategori_aset->id,
        ]);

        $kategori_aset->update($request->only('name'));

        return back()->with('success', 'Kategori aset berhasil diperbarui.');
    }

    public function destroy(KategoriAset $kategori_aset)
    {
        if ($kategori_aset->asets()->exists()) {
            return back()->with('error', 'Kategori ini tidak dapat dihapus karena masih memiliki aset.');
        }

        $kategori_aset->delete();

        return back()->with('success', 'Kategori aset berhasil dihapus.');
    }
}
