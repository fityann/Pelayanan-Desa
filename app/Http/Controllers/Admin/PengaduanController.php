<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    public function index(): View
    {
        $pengaduan = Pengaduan::with(['user', 'processor'])->latest()->paginate(15);

        $rekap = Pengaduan::selectRaw("kategori, COUNT(*) as total, 
            SUM(CASE WHEN status='diterima' THEN 1 ELSE 0 END) as diterima,
            SUM(CASE WHEN status='diproses' THEN 1 ELSE 0 END) as diproses,
            SUM(CASE WHEN status='selesai' THEN 1 ELSE 0 END) as selesai")
            ->groupBy('kategori')
            ->get();

        return view('admin.pengaduan.index', compact('pengaduan', 'rekap'));
    }

    public function create(): View
    {
        return view('admin.pengaduan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create($data);

        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim');
    }

    public function proses(Pengaduan $pengaduan): RedirectResponse
    {
        $pengaduan->update([
            'status' => 'diproses',
            'processed_by' => auth()->id(),
            'tanggal_diproses' => now(),
        ]);

        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan sedang diproses');
    }

    public function selesai(Request $request, Pengaduan $pengaduan): RedirectResponse
    {
        $request->validate(['tanggapan' => ['nullable', 'string']]);

        $pengaduan->update([
            'status' => 'selesai',
            'tanggapan' => $request->tanggapan,
            'tanggal_selesai' => now(),
        ]);

        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan selesai diproses');
    }

    public function destroy(Pengaduan $pengaduan): RedirectResponse
    {
        $pengaduan->delete();
        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan berhasil dihapus');
    }
}
