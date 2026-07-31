<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    public function create(): View
    {
        return view('warga.pengaduan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kategori' => ['required', 'in:Infrastruktur,Kebersihan,Pelayanan,Keamanan,Sosial,Lainnya'],
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'diterima',
            'sumber_akses' => request()->query('qr') === '1' ? 'qr_code' : 'web',
            'tanggal_diterima' => now(),
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create($data);

        return redirect()->route('dashboard')
            ->with('success', 'Pengaduan berhasil dikirim. Kami akan memproses segera.');
    }
}
