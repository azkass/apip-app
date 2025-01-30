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

        // Periksa role pengguna
        $user = Auth::user();
        if ($user->role != $role) {
            abort(403, 'Unauthorized action.'); // Jika role tidak sesuai, tampilkan error 403
        }

        return $next($request);
    }
}
