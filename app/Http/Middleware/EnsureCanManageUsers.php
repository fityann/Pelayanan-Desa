<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageUsers
{
    /**
     * Hanya Super Admin / Admin Desa yang boleh mengelola user & role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(auth()->user()->hasAnyRole(['Super Admin', 'Admin Desa']), 403);

        return $next($request);
    }
}
