<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuratController extends Controller
{
    public function index(): View
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();

        return view('warga.surat.index', compact('jenisSurat'));
    }

    public function create(JenisSurat $jenisSurat): View
    {
        abort_if(!$jenisSurat->aktif, 404);

        return view('warga.surat.create', compact('jenisSurat'));
    }

    public function store(Request $request, JenisSurat $jenisSurat): RedirectResponse
    {
        abort_if(!$jenisSurat->aktif, 404);

        $request->validate([
            'keterangan' => ['required', 'string', 'max:1000'],
            'file_pendukung' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('pengajuan-surat', 'public');
        }

        $pengajuan = PengajuanSurat::create([
            'user_id' => auth()->id(),
            'jenis_surat_id' => $jenisSurat->id,
            'status' => 'diajukan',
            'keterangan' => $request->keterangan,
            'file_pendukung' => $filePath,
            'tanggal_diajukan' => now(),
        ]);

        return redirect()->route('warga.surat.status', $pengajuan)
            ->with('success', 'Pengajuan surat berhasil dikirim. Pantau statusnya di sini.');
    }

    public function riwayat(): View
    {
        $pengajuan = PengajuanSurat::with('jenisSurat')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('warga.surat.riwayat', compact('pengajuan'));
    }

    public function status(PengajuanSurat $pengajuan): View
    {
        $this->authorizeView($pengajuan);

        $pengajuan->load('jenisSurat');

        return view('warga.surat.status', compact('pengajuan'));
    }

    public function pdf(PengajuanSurat $pengajuan): \Illuminate\Http\Response
    {
        $this->authorizeView($pengajuan);

        abort_unless(in_array($pengajuan->status, ['disetujui_kades', 'menunggu_ttd_fisik', 'selesai']), 403);

        $pdf = Pdf::loadView('pdf.surat', ['surat' => $pengajuan])
            ->setPaper('a4', 'portrait');

        $filename = (str_replace(['/', '\\'], '-', $pengajuan->nomor_surat) ?? 'draft')
            . '-' . str_replace([' ', '/', '\\'], '-', $pengajuan->user->name) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Hanya pemilik pengajuan atau perangkat desa yang boleh melihat.
     */
    private function authorizeView(PengajuanSurat $pengajuan): void
    {
        $user = auth()->user();

        $staff = $user->hasAnyRole(['Super Admin', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara', 'Admin Desa']);

        abort_unless($staff || $pengajuan->user_id === $user->id, 403);
    }
}
