<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::with('kategori')
            ->where('status', '!=', \App\Enums\StatusAset::DIHAPUS); // Optionally hide deleted ones if not already soft deleted

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

        $asets = $query->latest()->paginate(12)->withQueryString();
        $kategoriAsets = KategoriAset::orderBy('name')->get();

        return view('assets.index', compact('asets', 'kategoriAsets'));
    }
}
