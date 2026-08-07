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
            return redirect()->route('warga.rt.login', [
                'rt' => $request->route('rt'),
                'rw' => $request->route('rw'),
            ]);
        }

        $user = Auth::guard('warga')->user();

        // Warga: RT/RW di URL harus sama dengan wilayah yang dimiliki
        $userRt = $user->penduduk?->rt ?? $user->rt;
        $userRw = $user->penduduk?->rw ?? $user->rw;

        if (is_null($userRt) || (int) $userRt !== (int) $request->route('rt')
            || (int) $userRw !== (int) $request->route('rw')) {
            abort(403, 'Anda hanya dapat mengakses layanan di RT/RW wilayah Anda.');
        }

        return $next($request);
    }
}
