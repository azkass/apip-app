<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Periksa role pengguna (bisa multi-role dengan OR, pisah |)
        $user = Auth::user();
        $roles = explode('|', $role);
        if (!in_array($user->role, $roles)) {
            abort(403, 'Mohon Maaf, Anda Tidak Diizinkan'); // Jika role tidak sesuai, tampilkan error 403
        }

        return $next($request);
    }
}
