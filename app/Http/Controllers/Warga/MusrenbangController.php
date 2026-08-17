<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Musrenbang;
use Illuminate\Http\Request;

class MusrenbangController extends Controller
{
    public function index(Request $request)
    {
        $query = Musrenbang::with(['pengusul', 'wilayah']);
        
        $tahun = $request->has('tahun') ? $request->input('tahun') : date('Y');
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        $musrenbangs = $query->latest('tanggal_musrenbang')->latest()->paginate(15)->withQueryString();
        
        return view('warga.musrenbang.index', compact('musrenbangs', 'tahun'));
    }

    public function show(Musrenbang $musrenbang)
    {
        $musrenbang->load(['pengusul', 'dokumen', 'suara.user']);
        
        // Cek suara user saat ini jika login
        $userVote = null;
        if (auth('warga')->check()) {
            $userVote = $musrenbang->suara()->where('user_id', auth('warga')->id())->first();
        }

        // Hitung statistik suara
        $stats = [
            'dukung' => $musrenbang->suara()->where('tipe_suara', 'dukung')->count(),
            'tolak' => $musrenbang->suara()->where('tipe_suara', 'tolak')->count(),
            'abstain' => $musrenbang->suara()->where('tipe_suara', 'abstain')->count(),
        ];
        
        return view('warga.musrenbang.show', compact('musrenbang', 'userVote', 'stats'));
    }

    public function support(Request $request, Musrenbang $musrenbang)
    {
        $request->validate([
            'tipe_suara' => 'required|in:dukung,tolak,abstain',
            'alasan' => 'nullable|string|max:500',
        ]);

        $existing = $musrenbang->suara()->where('user_id', auth('warga')->id())->first();

        if ($existing) {
            $existing->update([
                'tipe_suara' => $request->tipe_suara,
                'alasan' => $request->alasan,
            ]);
            $message = 'Suara berhasil diperbarui.';
        } else {
            $musrenbang->suara()->create([
                'user_id' => auth('warga')->id(),
                'tipe_suara' => $request->tipe_suara,
                'alasan' => $request->alasan,
            ]);
            $message = 'Suara berhasil diberikan. Terima kasih atas partisipasi Anda!';
        }

        // Update cache kolom jumlah pendukung di tabel utama
        $musrenbang->update([
            'jumlah_pendukung' => $musrenbang->suara()->where('tipe_suara', 'dukung')->count()
        ]);

        return back()->with('success', $message);
    }
}
