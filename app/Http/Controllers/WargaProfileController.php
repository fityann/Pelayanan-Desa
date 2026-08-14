<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penduduk;

class WargaProfileController extends Controller
{
    public function index($rt)
    {
        $user = auth('warga')->user();
        
        $pengaduans = $user->pengaduans()->latest()->take(5)->get();
        $surats = $user->pengajuanSurats()->latest()->take(5)->get();

        return view('warga.profil.index', compact('rt', 'user', 'pengaduans', 'surats'));
    }

    public function update(Request $request, $rt)
    {
        /** @var \App\Models\User $user */
        $user = auth('warga')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // If connected to penduduk, update it as well (penduduk table uses 'nama')
        if ($user->penduduk) {
            $user->penduduk->update([
                'nama' => $validated['name'],
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
