<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminGateController extends Controller
{
    /**
     * Step 1: Beralih dari Form Login Warga ke Login Admin
     */
    public function showGate()
    {
        if (session('admin_gate_passed')) {
            return redirect()->route('admin.login.form');
        }

        return redirect()->route('warga.rt.login', ['rt' => '01'])
            ->with('info', 'Untuk mengakses Portal Admin, silakan masukkan NIK & Nama Perangkat Desa atau Kode Unik pada form ini.');
    }

    /**
     * Step 1: Verifikasi Kode Khusus Akses Admin (Legacy / Direct)
     */
    public function verifyGate(Request $request)
    {
        session(['admin_gate_passed' => true]);

        return redirect()->route('admin.login.form')
            ->with('success', 'Akses Admin berhasil diverifikasi! Silakan masuk dengan akun perangkat desa Anda.');
    }

    /**
     * Step 2: Form Login Kredensial Admin
     */
    public function showAdminLogin()
    {
        if (!session('admin_gate_passed')) {
            return redirect()->route('warga.rt.login', ['rt' => '01'])
                ->with('error', 'Silakan masukkan NIK & Nama Perangkat Desa atau Kode Unik pada form login terlebih dahulu.');
        }

        return view('auth.admin-login');
    }

    /**
     * Step 2: Autentikasi Kredensial Admin
     */
    public function authenticateAdmin(Request $request)
    {
        if (!session('admin_gate_passed')) {
            return redirect()->route('admin.gate.show')
                ->with('error', 'Sesi kode akses admin telah berakhir.');
        }

        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($request->login);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nik';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withInput()->withErrors([
                'login' => 'Email/NIK atau Password tidak sesuai.',
            ]);
        }

        if (!$user->hasRole(['Super Admin', 'Admin Desa', 'Kepala Desa', 'Sekretaris Desa', 'Bendahara'])) {
            return back()->withInput()->withErrors([
                'login' => 'Akun Anda tidak memiliki otoritas sebagai Perangkat Desa.',
            ]);
        }

        Auth::guard('web')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang di Panel Administrasi Desa, ' . $user->name . '!');
    }
}
