<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $socialUser = Socialite::driver('google')->user();
        $registeredUser = User::where('google_id', $socialUser->id)->first();

        if (!$registeredUser) {
            $user = User::updateOrCreate([
                'google_id' => $socialUser->id,
            ], [
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => bcrypt('password'),
                'google_token' => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken,
            ]);
            Auth::login($user);
            // return redirect('/');
        } else {
            Auth::login($registeredUser);
            // return redirect('/');
        }
        // Redirect after login
        if (Auth::user()->role == 'admin') {
            return redirect('/admin/dashboard');
        } else if (Auth::user()->role == 'pjk') {
            return redirect('/penanggungjawab/dashboard');
        } else if (Auth::user()->role == 'perencana') {
            return redirect('/perencana/dashboard');
        } else if (Auth::user()->role == 'pegawai') {
        return redirect('/pegawai/dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna

        $request->session()->invalidate(); // Menghapus session
        $request->session()->regenerateToken(); // Regenerasi token CSRF

        return redirect('/login'); // Redirect ke halaman login setelah logout
    }

    public function list()
    {
        $users = DB::select("SELECT id, name, email, role FROM users");
        return view('admin.listrole', compact('users'));
    }

    public function edit($id)
    {
        $user = DB::select("SELECT id, name, email, role FROM users WHERE id = ?", [$id]);
        // Karena DB::select() mengembalikan array, ambil elemen pertama
        $user = $user ? $user[0] : null;
        return view('admin.editrole', compact('user'));
    }

    public function update(Request $request, $id)
    {
        DB::update("UPDATE users SET role = ? WHERE id = ?", [$request->role, $id]);
        return redirect('/admin/list');
    }
}
