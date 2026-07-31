<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformasiController extends Controller
{
    public function index(): View
    {
        $informasi = Informasi::with('user')->latest()->paginate(15);
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
            'gambar' => ['nullable', 'image', 'max:2048'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

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
            'gambar' => ['nullable', 'image', 'max:2048'],
            'tanggal_kegiatan' => ['nullable', 'date'],
            'lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $request->all();

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

    public function publik(): View
    {
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
