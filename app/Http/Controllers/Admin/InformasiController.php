<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Informasi::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('ringkasan', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $informasi = $query->latest()->paginate(15)->withQueryString();
        return view('admin.informasi.index', compact('informasi'));
    }

    public function create(): View
    {
        return view('admin.informasi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string'],
            'kategori' => ['required', 'in:berita,pengumuman,agenda'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
        ]);

        $data = $request->only([
            'judul', 'isi', 'kategori', 'tanggal_kegiatan', 'lokasi',
        ]);
        $data['user_id'] = auth()->id();
        $data['rt'] = $request->filled('rt') ? $request->rt : null;
        $data['rw'] = $request->filled('rw') ? $request->rw : null;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('informasi', 'public');
        }

        if ($request->has('publish')) {
            $data['published'] = true;
            $data['published_at'] = now();
        }

        Informasi::create($data);

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil ditambahkan');
    }

    public function show(Informasi $informasi): View
    {
        $informasi->load('user');
        return view('admin.informasi.show', compact('informasi'));
    }

    public function edit(Informasi $informasi): View
    {
        return view('admin.informasi.edit', compact('informasi'));
    }

    public function update(Request $request, Informasi $informasi): RedirectResponse
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['required', 'string'],
            'kategori' => ['required', 'in:berita,pengumuman,agenda'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:3'],
            'rw' => ['nullable', 'string', 'max:3'],
        ]);

        $data = $request->only([
            'judul', 'isi', 'kategori', 'tanggal_kegiatan', 'lokasi',
        ]);
        $data['rt'] = $request->filled('rt') ? $request->rt : null;
        $data['rw'] = $request->filled('rw') ? $request->rw : null;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('informasi', 'public');
        }

        $data['published'] = $request->has('publish');
        if ($data['published'] && !$informasi->published_at) {
            $data['published_at'] = now();
        }

        $informasi->update($data);

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil diperbarui');
    }

    public function publish(Informasi $informasi): RedirectResponse
    {
        $informasi->update([
            'published' => true,
            'published_at' => now(),
        ]);

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi dipublikasikan');
    }

    public function destroy(Informasi $informasi): RedirectResponse
    {
        $informasi->delete();
        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil dihapus');
    }

    public function publik(Request $request): View
    {
        // Dukung konteks RT/RW lewat query (?rt=01&rw=01) agar navbar tetap scoped
        if ($request->has('rt') && $request->has('rw')) {
            session(['warga_rt' => $request->rt, 'warga_rw' => $request->rw]);
        }

        $berita = Informasi::where('published', true)
            ->where('kategori', 'berita')
            ->latest('published_at')
            ->paginate(6);

        $pengumuman = Informasi::where('published', true)
            ->where('kategori', 'pengumuman')
            ->latest('published_at')
            ->get();

        $agenda = Informasi::where('published', true)
            ->where('kategori', 'agenda')
            ->where('tanggal_kegiatan', '>=', now())
            ->orderBy('tanggal_kegiatan')
            ->get();

        return view('informasi-publik', compact('berita', 'pengumuman', 'agenda'));
    }
}
