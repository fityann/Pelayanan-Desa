<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request using NIK & Nama Sesuai KTP.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'digits:16'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $namaNormal = preg_replace('/\s+/', ' ', mb_strtolower(trim($request->nama)));
        $nikInput = trim($request->nik);
        $namaUpper = strtoupper(preg_replace('/\s+/', '', trim($request->nama)));

        // 0. Cek Kode Unik Khusus Akses Portal Admin pada Form Login Warga
        if ($nikInput === '0000000000000000' && in_array($namaUpper, ['PUSPAMUKTI2026', 'ADMIN', 'ADMIN2026', 'PUSPAMUKTI'])) {
            session(['admin_gate_passed' => true]);
            return redirect()->route('admin.login.form')
                ->with('success', 'Kode Unik Akses Admin Terverifikasi! Silakan masuk dengan akun Perangkat Desa Anda.');
        }

        // 1. Cari penduduk yang terdaftar di database desa
        $penduduk = \App\Models\Penduduk::where('nik', $request->nik)->get()
            ->first(fn($pd) => preg_replace('/\s+/', ' ', mb_strtolower(trim($pd->nama))) === $namaNormal);

        $user = null;

        if ($penduduk) {
            if ($penduduk->user_id && $u = \App\Models\User::find($penduduk->user_id)) {
                $user = $u;
            } else {
                $user = \App\Models\User::where('nik', $penduduk->nik)->first();
                if (!$user) {
                    $user = \App\Models\User::create([
                        'name' => $penduduk->nama,
                        'email' => $penduduk->nik . '@silapu.local',
                        'email_verified_at' => now(),
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(40)),
                        'address' => $penduduk->alamat,
                        'rt' => $penduduk->rt,
                        'rw' => $penduduk->rw,
                    ]);
                }
            }

            $user->update([
                'name' => $penduduk->nama,
                'address' => $penduduk->alamat,
                'rt' => $penduduk->rt,
                'rw' => $penduduk->rw,
            ]);

            if (!$user->hasRole('Warga') && !$user->roles()->exists()) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Warga', 'guard_name' => 'web']);
                $user->assignRole('Warga');
            }

            if ((int) $penduduk->user_id !== $user->id) {
                $penduduk->update(['user_id' => $user->id]);
            }
        } else {
            // Fallback cari di tabel Users (untuk Staff/Admin)
            $user = \App\Models\User::where('nik', $request->nik)->get()
                ->first(fn($u) => preg_replace('/\s+/', ' ', mb_strtolower(trim($u->name))) === $namaNormal);
        }

        if (!$user) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK dan Nama Lengkap tidak terdaftar / tidak sesuai dengan data KTP kependudukan desa. Silakan hubungi petugas desa.',
            ]);
        }

        // Jika user adalah Admin/Perangkat Desa -> alihkan ke Form Password Admin (Step 2)
        if ($user->hasRole(['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'])) {
            session([
                'admin_gate_passed' => true,
                'admin_pending_nik' => $user->nik,
                'admin_pending_name' => $user->name,
            ]);

            return redirect()->route('admin.login.form')
                ->with('success', 'NIK & Nama Perangkat Desa terverifikasi (' . $user->name . '). Silakan masukkan password admin Anda untuk masuk ke Dashboard.');
        }

        Auth::guard('web')->login($user, true);
        Auth::guard('warga')->login($user, true);
        $request->session()->regenerate();

        $userRt = sprintf('%02d', $user->rt ?? '01');
        $userRw = sprintf('%02d', $user->rw ?? '01');
        session(['warga_rt' => $userRt, 'warga_rw' => $userRw]);

        return redirect()->intended(route('warga.rt.surat.index', ['rt' => $userRt, 'rw' => $userRw]))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
