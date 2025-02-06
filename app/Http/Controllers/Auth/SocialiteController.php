<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
        if (Auth::user()->role == 'admin') {
            return redirect('/admin/dashboard');
        } else {
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
}
