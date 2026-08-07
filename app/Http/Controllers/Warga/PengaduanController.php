<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function create()
    {
        // Use the QR code interface for pengaduan
        return view('warga.pengaduan-qr');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'nama' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB per file
            'sumber_akses' => 'required|string',
            'lokasi_qr' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
        ]);

        // Create pengaduan
        $pengaduan = new Pengaduan();
        $pengaduan->user_id = Auth::guard('warga')->id();
        $pengaduan->kategori = $validated['kategori'];
        $pengaduan->judul = $validated['judul'];
        $pengaduan->deskripsi = $validated['deskripsi'];
        $pengaduan->nama_pelapor = $validated['nama'];
        $pengaduan->whatsapp = $validated['whatsapp'];
        $pengaduan->sumber_akses = $validated['sumber_akses'];
        $pengaduan->status = 'diterima';
        
        // Add location data
        if ($request->filled('lokasi_qr')) {
            $pengaduan->lokasi_qr = $validated['lokasi_qr'];
        }
        
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $pengaduan->latitude = $validated['latitude'];
            $pengaduan->longitude = $validated['longitude'];
        }
        
        if ($request->filled('rt')) {
            $pengaduan->rt = $validated['rt'];
        }
        
        if ($request->filled('rw')) {
            $pengaduan->rw = $validated['rw'];
        }

        // Handle photo upload (multiple images)
        if ($request->hasFile('foto')) {
            $fotoPaths = [];
            foreach ($request->file('foto') as $index => $foto) {
                $filename = 'pengaduan_' . Str::random(10) . '_' . $index . '.' . $foto->getClientOriginalExtension();
                $fotoPaths[] = $foto->storeAs('pengaduan', $filename, 'public');
            }
            $pengaduan->foto = json_encode($fotoPaths);
        }

        // Generate ticket ID
        $pengaduan->tiket_id = 'TKT-' . date('Ymd') . '-' . Str::random(6);
        
        $pengaduan->save();

        // Kirim notifikasi ke semua staff/admin
        Notification::kirimKeStaff([
            'judul' => 'Pengaduan baru dari ' . $validated['nama'],
            'pesan' => $validated['judul'] . ' (' . $validated['kategori'] . ')',
            'tipe' => 'pengaduan',
            'icon' => 'campaign',
            'warna' => 'bg-error/10 text-error',
            'link' => route('admin.pengaduan.index'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim',
            'tiket_id' => $pengaduan->tiket_id,
            'redirect' => route('dashboard')
        ], 201);
    }

    private function notifyAdmins(Pengaduan $pengaduan)
    {
        // You can implement notification system here
        // For example: Email, WhatsApp, or in-app notification
        // Example using Laravel Notification:
        // $admins = User::role(['Super Admin', 'Admin Desa'])->get();
        // Notification::send($admins, new NewPengaduanNotification($pengaduan));
    }
}
