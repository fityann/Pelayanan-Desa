<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAsetRequest;
use App\Http\Requests\UpdateAsetRequest;
use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with('kategori');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('asset_category_id', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('condition', $request->kondisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $asets = $query->latest()->paginate(10)->withQueryString();
        $kategoriAsets = KategoriAset::orderBy('name')->get();

        return view('admin.assets.index', compact('asets', 'kategoriAsets'));
    }

    public function create()
    {
        $kategoriAsets = KategoriAset::orderBy('name')->get();
        return view('admin.assets.create', compact('kategoriAsets'));
    }

    public function store(StoreAsetRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('assets_photos', 'public');
            }

            Aset::create($data);

            DB::commit();

            return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Aset $asset)
    {
        $asset->load('kategori');
        return view('admin.assets.show', compact('asset'));
    }

    public function edit(Aset $asset)
    {
        $kategoriAsets = KategoriAset::orderBy('name')->get();
        return view('admin.assets.edit', compact('asset', 'kategoriAsets'));
    }

    public function update(UpdateAsetRequest $request, Aset $asset)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            if ($request->hasFile('photo')) {
                if ($asset->photo) {
                    Storage::disk('public')->delete($asset->photo);
                }
                $data['photo'] = $request->file('photo')->store('assets_photos', 'public');
            }

            $asset->update($data);

            DB::commit();

            return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Aset $asset)
    {
        try {
            DB::beginTransaction();
            
            // Note: because of soft deletes, we might keep the photo
            $asset->delete();
            
            DB::commit();

            return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
