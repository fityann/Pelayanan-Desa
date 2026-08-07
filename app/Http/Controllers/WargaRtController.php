<?php

namespace App\Http\Controllers;

use App\Models\RtQrCode;
use App\Models\Pengaduan;
use App\Models\Penduduk;
use App\Models\Apbde;
use App\Models\Informasi;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WargaRtController extends Controller
{
    public function landing(Request $request, $rt, $rw)
    {
        // Validasi RT/RW
        if (!preg_match('/^\d{1,3}$/', $rt) || !preg_match('/^\d{1,3}$/', $rw)) {
            abort(400, 'Format RT/RW tidak valid');
        }

        // Simpan konteks RT/RW ke session agar navbar tetap konsisten
        // saat warga berpindah ke halaman publik (APBDes, Berita, Info Desa).
        session(['warga_rt' => $rt, 'warga_rw' => $rw]);

        // Temukan atau buat QR code untuk RT/RW ini
        $qrCode = RtQrCode::firstOrCreate(
            ['rt' => $rt, 'rw' => $rw],
            [
                'nama_rt' => "RT $rt RW $rw",
                'deskripsi' => "QR Code untuk warga RT $rt RW $rw",
                'status' => 'aktif'
            ]
        );

        // Log scan
        $this->logQrCodeScan($qrCode, $request);

        // Increment scan count
        $qrCode->incrementScanCount();

        // Get stats
        $stats = $qrCode->stats;

        // Get pengaduan stats untuk RT ini
        $pengaduanStats = $this->getPengaduanStats($rt, $rw);

        // Berita & agenda untuk wilayah RT/RW ini
        $beritaTerbaru = Informasi::where('published', true)
            ->where('kategori', 'berita')
            ->untukWilayah($rt, $rw)
            ->latest('published_at')
            ->take(3)
            ->get();

        $agendaTerdekat = Informasi::where('published', true)
            ->where('kategori', 'agenda')
            ->untukWilayah($rt, $rw)
            ->where('tanggal_kegiatan', '>=', now())
            ->orderBy('tanggal_kegiatan')
            ->take(3)
            ->get();

        return view('warga.rt-landing', compact('rt', 'rw', 'qrCode', 'stats', 'pengaduanStats', 'beritaTerbaru', 'agendaTerdekat'));
    }

    private function logQrCodeScan(RtQrCode $qrCode, Request $request)
    {
        $userAgent = $request->userAgent() ?? '';
        
        $deviceType = 'desktop';
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent)) {
            $deviceType = 'mobile';
        }
        if (preg_match('/iPad|Tablet/i', $userAgent)) {
            $deviceType = 'tablet';
        }

        $qrCode->logs()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'keterangan' => 'Scan QR Code landing page'
        ]);
    }

    private function getPengaduanStats($rt, $rw)
    {
        return [
            'total_pengaduan' => Pengaduan::where('rt', $rt)
                ->where('rw', $rw)
                ->count(),
            'pengaduan_selesai' => Pengaduan::where('rt', $rt)
                ->where('rw', $rw)
                ->where('status', 'selesai')
                ->count(),
            'pengaduan_diproses' => Pengaduan::where('rt', $rt)
                ->where('rw', $rw)
                ->where('status', 'diproses')
                ->count(),
            'kategori_top' => Pengaduan::where('rt', $rt)
                ->where('rw', $rw)
                ->selectRaw('kategori, COUNT(*) as jumlah')
                ->groupBy('kategori')
                ->orderBy('jumlah', 'desc')
                ->first()
        ];
    }

    public function showLogin($rt, $rw)
    {
        if (!preg_match('/^\d{1,3}$/', $rt) || !preg_match('/^\d{1,3}$/', $rw)) {
            abort(400, 'Format RT/RW tidak valid');
        }

        return view('warga.login', compact('rt', 'rw'));
    }

    public function authenticateWarga(Request $request, $rt, $rw)
    {
        if (!preg_match('/^\d{1,3}$/', $rt) || !preg_match('/^\d{1,3}$/', $rw)) {
            abort(400, 'Format RT/RW tidak valid');
        }

        $request->validate([
            'nik' => ['required', 'digits:16'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        // Normalisasi nama: huruf kecil + buang spasi berlebih di tengah & tepi
        $namaNormal = preg_replace('/\s+/', ' ', mb_strtolower(trim($request->nama)));

        // Cari penduduk yang terdaftar di panel admin sesuai wilayah RT/RW ini
        // (RT/RW dibandingkan secara numerik agar "5" == "05", nama case-insensitive & toleran spasi)
        $penduduk = Penduduk::query()
            ->where('nik', $request->nik)
            ->whereRaw('rt + 0 = ?', [(int) $rt])
            ->whereRaw('rw + 0 = ?', [(int) $rw])
            ->get()
            ->first(fn($pd) => preg_replace('/\s+/', ' ', mb_strtolower(trim($pd->nama))) === $namaNormal);

        if (!$penduduk) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK dan nama tidak terdaftar sebagai warga RT ' . $rt . ' RW ' . $rw . '. Hubungi petugas desa jika data Anda belum tercatat.',
            ]);
        }

        $user = $this->getOrCreateUserFromPenduduk($penduduk);

        Auth::guard('warga')->login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('warga.rt.surat.index', ['rt' => $rt, 'rw' => $rw]))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logoutWarga(Request $request, $rt, $rw)
    {
        Auth::guard('warga')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('warga.rt.landing', ['rt' => $rt, 'rw' => $rw])
            ->with('success', 'Anda berhasil keluar.');
    }

    private function getOrCreateUserFromPenduduk(Penduduk $penduduk): User
    {
        // Reuse user yang masih valid & terhubung
        if ($penduduk->user_id && $user = User::find($penduduk->user_id)) {
            return $user;
        }

        // Reuse user yang sudah pernah dibuat dari NIK yang sama (atau email otomatis yang sama)
        $user = User::where('nik', $penduduk->nik)
            ->orWhere('email', $penduduk->nik . '@silapu.local')
            ->first();
        if (!$user) {
            $user = User::create([
                'name' => $penduduk->nama,
                'email' => $penduduk->nik . '@silapu.local',
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
                'address' => $penduduk->alamat,
                'rt' => $penduduk->rt,
                'rw' => $penduduk->rw,
            ]);
        }

        // Pastikan data profil sinkron dengan data penduduk
        $user->update([
            'name' => $penduduk->nama,
            'address' => $penduduk->alamat,
            'rt' => $penduduk->rt,
            'rw' => $penduduk->rw,
        ]);

        if (!$user->hasRole('Warga')) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Warga', 'guard_name' => 'web']);
            $user->assignRole('Warga');
        }

        // Perbaiki relasi penduduk -> user (termasuk user_id stale)
        if ((int) $penduduk->user_id !== $user->id) {
            $penduduk->update(['user_id' => $user->id]);
        }

        return $user;
    }

    public function createPengaduan(Request $request, $rt, $rw)
    {
        // Validasi request
        $request->validate([
            'nama' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20',
            'kategori' => 'required|string|max:100',
            'judul' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Create pengaduan
        $pengaduan = new Pengaduan();
        $pengaduan->user_id = auth('warga')->id() ?? null;
        $pengaduan->nama_pelapor = $request->nama;
        $pengaduan->whatsapp = $request->whatsapp;
        $pengaduan->kategori = $request->kategori;
        $pengaduan->judul = $request->judul;
        $pengaduan->deskripsi = $request->deskripsi;
        $pengaduan->rt = $rt;
        $pengaduan->rw = $rw;
        $pengaduan->sumber_akses = 'qr_rt';
        $pengaduan->status = 'diterima';

        // Handle foto upload (multiple images)
        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $index => $foto) {
                $filename = 'pengaduan_rt_' . $rt . '_' . time() . '_' . $index . '.' . $foto->getClientOriginalExtension();
                $fotoPaths[] = $foto->storeAs('pengaduan/rt', $filename, 'public');
            }
        }
        $pengaduan->foto = $fotoPaths ? json_encode($fotoPaths) : null;

        // Generate ticket ID
        $pengaduan->tiket_id = 'RT' . $rt . '-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        
        $pengaduan->save();

        // Notifikasi ke semua staff/admin
        Notification::kirimKeStaff([
            'judul' => 'Pengaduan baru dari warga RT ' . $rt,
            'pesan' => $request->nama . ' melaporkan: ' . $request->judul . ' (' . $request->kategori . ')',
            'tipe' => 'pengaduan',
            'icon' => 'campaign',
            'warna' => 'bg-error/10 text-error',
            'link' => route('admin.pengaduan.index'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim',
            'tiket_id' => $pengaduan->tiket_id
        ]);
    }

    public function infoDesa($rt, $rw)
    {
        // Get info desa terkait RT/RW ini
        $infoDesa = [
            'apbdes' => Apbde::where('tahun', date('Y'))
                ->where('status', 'dipublikasikan')
                ->orderBy('tahun', 'desc')
                ->take(10)
                ->get(),
            'berita' => Informasi::where('published', true)
                ->untukWilayah($rt, $rw)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get(),
            'pengumuman' => Informasi::where('published', true)
                ->where('kategori', 'pengumuman')
                ->untukWilayah($rt, $rw)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'agenda' => Informasi::where('published', true)
                ->where('kategori', 'agenda')
                ->untukWilayah($rt, $rw)
                ->where('tanggal_kegiatan', '>=', now())
                ->orderBy('tanggal_kegiatan')
                ->take(5)
                ->get(),
            'layanan' => $this->getLayananList(),
            'kontak_desa' => $this->getKontakDesa()
        ];

        return view('warga.info-desa', compact('rt', 'rw', 'infoDesa'));
    }

    private function getLayananList()
    {
        return [
            ['nama' => 'Pengaduan', 'icon' => 'campaign', 'desc' => 'Laporkan masalah di lingkungan RT'],
            ['nama' => 'Surat Online', 'icon' => 'edit_note', 'desc' => 'Ajukan surat keterangan online'],
            ['nama' => 'Data Penduduk', 'icon' => 'groups', 'desc' => 'Informasi data penduduk RT'],
            ['nama' => 'Kegiatan RT', 'icon' => 'event', 'desc' => 'Jadwal kegiatan RT'],
            ['nama' => 'APBDes', 'icon' => 'account_balance', 'desc' => 'Anggaran dan belanja desa'],
            ['nama' => 'Info Desa', 'icon' => 'newspaper', 'desc' => 'Berita dan pengumuman desa']
        ];
    }

    private function getKontakDesa()
    {
        return [
            'desa' => 'Puspamukti',
            'alamat' => 'Jl. Raya Puspamukti No. 1',
            'telepon' => '(0281) 123456',
            'whatsapp' => '0812-3456-7890',
            'email' => 'desa@puspamukti.desa.id',
            'jam_operasional' => 'Senin - Jumat: 08:00 - 16:00',
            'ketua_rt' => [
                'umum' => 'Bapak RT (0813-1234-567)',
                'khusus' => 'Ibu RW (0821-9876-543)'
            ]
        ];
    }

    public function downloadQR(Request $request, $rt, $rw)
    {
        $qrCode = RtQrCode::where('rt', $rt)->where('rw', $rw)->firstOrFail();
        
        // QR Code dicetak oleh admin secara eksternal (di halaman admin qr-links),
        // jadi alihkan admin ke halaman daftar link jika ingin mencetak ulang.
        return redirect()->route('admin.qr-links.index');
    }
}