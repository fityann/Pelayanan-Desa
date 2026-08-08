<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WargaAuth
{
    /**
     * Batasi akses ke halaman khusus warga RT/RW.
     * - Belum login: arahkan ke halaman login warga (NIK + Nama).
     * - Warga: hanya boleh mengakses RT/RW miliknya (sesuai data penduduk).
     * - Staff/Admin: bebas mengakses semua RT/RW.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('warga')->check()) {
            $rt = $request->route('rt') ?? session('warga_rt', '01');

            return redirect()->route('warga.rt.login', [
                'rt' => $rt,
            ])->with('error', 'Silakan masuk terlebih dahulu menggunakan NIK dan Nama sesuai KTP.');
        }

        $user = Auth::guard('warga')->user();

        // Jika route memiliki parameter RT dan RW, pastikan disesuaikan dengan wilayah user
        if ($request->route('rt') && $request->route('rw')) {
            $userRt = $user->penduduk?->rt ?? $user->rt;
            $userRw = $user->penduduk?->rw ?? $user->rw;

            if (!is_null($userRt) && !is_null($userRw)) {
                if ((int) $userRt !== (int) $request->route('rt') || (int) $userRw !== (int) $request->route('rw')) {
                    session(['warga_rt' => sprintf('%02d', $userRt), 'warga_rw' => sprintf('%02d', $userRw)]);
                }
            }
        }

        return $next($request);
    }
}
