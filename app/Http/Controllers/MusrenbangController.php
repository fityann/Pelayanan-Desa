<?php

namespace App\Http\Controllers;

use App\Models\Musrenbang;
use App\Models\MusrenbangDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusrenbangController extends Controller
{
    public function index(Request $request)
    {
        $query = Musrenbang::with(['pengusul', 'wilayah']);
        
        if ($request->filled('tahun')) {
            $query->tahun($request->tahun);
        }
        
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        if ($request->filled('prioritas')) {
            $query->prioritas($request->prioritas);
        }
        
        $musrenbang = $query->latest()->paginate(15)->withQueryString();
        
        $stats = [
            'total' => Musrenbang::count(),
            'diusulkan' => Musrenbang::where('status_usulan', 'diusulkan')->count(),
            'disetujui' => Musrenbang::where('status_usulan', 'disetujui')->count(),
            'total_alokasi' => Musrenbang::where('status_usulan', 'disetujui')->sum('alokasi_anggaran'),
        ];
        
        return view('admin.musrenbang.index', compact('musrenbang', 'stats'));
    }

    public function create()
    {
        return view('admin.musrenbang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|size:4',
            'judul_kegiatan' => 'required|string|max:500',
            'deskripsi_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'estimasi_biaya' => 'required|numeric|min:0',
            'sumber_dana' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi,sangat_tinggi',
            'tanggal_musrenbang' => 'nullable|date',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
            'tipe_dokumen.*' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        $data['pengusul_id'] = auth()->id();
        $data['status_usulan'] = 'diusulkan';

        $musrenbang = Musrenbang::create($data);

        // Upload dokumen perencanaan (RKPD, RKP, proposal, dll)
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $index => $file) {
                $path = $file->store('musrenbang/dokumen', 'public');
                MusrenbangDokumen::create([
                    'musrenbang_id' => $musrenbang->id,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'tipe_dokumen' => $request->input('tipe_dokumen')[$index] ?? 'proposal',
                    'path_dokumen' => $path,
                ]);
            }
        }

        return redirect()->route('admin.musrenbang.show', $musrenbang)
            ->with('success', 'Usulan Musrenbang berhasil diajukan');
    }

    public function show(Musrenbang $musrenbang)
    {
        $musrenbang->load(['pengusul', 'verifikator', 'reviewer', 'dokumen', 'suara.user']);
        return view('admin.musrenbang.show', compact('musrenbang'));
    }

    public function verify(Request $request, Musrenbang $musrenbang)
    {
        $musrenbang->update([
            'status_usulan' => 'diverifikasi',
            'verifikator_id' => auth()->id(),
        ]);

        return back()->with('success', 'Usulan berhasil diverifikasi');
    }

    public function review(Request $request, Musrenbang $musrenbang)
    {
        $request->validate([
            'hasil_musrenbang' => 'required|in:layak,revisi,ditunda,ditolak',
            'catatan_review' => 'nullable|string',
        ]);

        $musrenbang->update([
            'status_usulan' => 'direview',
            'reviewer_id' => auth()->id(),
            'hasil_musrenbang' => $request->hasil_musrenbang,
            'catatan_review' => $request->catatan_review,
        ]);

        return back()->with('success', 'Review usulan berhasil disimpan');
    }

    public function approve(Request $request, Musrenbang $musrenbang)
    {
        $request->validate([
            'alokasi_anggaran' => 'required|numeric|min:0',
        ]);

        $musrenbang->update([
            'status_usulan' => 'disetujui',
            'alokasi_anggaran' => $request->alokasi_anggaran,
        ]);

        return back()->with('success', 'Usulan disetujui dengan alokasi anggaran');
    }

    public function support(Request $request, Musrenbang $musrenbang)
    {
        $request->validate([
            'tipe_suara' => 'required|in:dukung,tolak,abstain',
            'alasan' => 'nullable|string',
        ]);

        // Check if user already voted
        $existing = $musrenbang->suara()
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            $existing->update([
                'tipe_suara' => $request->tipe_suara,
                'alasan' => $request->alasan,
            ]);
        } else {
            $musrenbang->suara()->create([
                'user_id' => auth()->id(),
                'tipe_suara' => $request->tipe_suara,
                'alasan' => $request->alasan,
            ]);
        }

        return back()->with('success', 'Suara berhasil dicatat');
    }
}