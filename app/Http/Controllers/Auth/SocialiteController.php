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
        return Socialite::driver("google")->redirect();
    }

    public function callback()
    {
        $socialUser = Socialite::driver("google")->user();
        $registeredUser = User::where("google_id", $socialUser->id)->first();

        if (!$registeredUser) {
            $user = User::updateOrCreate(
                [
                    "google_id" => $socialUser->id,
                ],
                [
                    "name" => $socialUser->name,
                    "email" => $socialUser->email,
                    "password" => bcrypt("password"),
                    "google_token" => $socialUser->token,
                    "active_role" => null, // Set null untuk menggunakan role default
                ],
            );
            Auth::login($user);
            return redirect("/");
        } else {
            Auth::login($registeredUser);
            return redirect("/");
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna
        $request->session()->invalidate(); // Menghapus session
        $request->session()->regenerateToken(); // Regenerasi token CSRF
        return redirect("/login"); // Redirect ke halaman login setelah logout
    }

    public function list()
    {
        $users = DB::select("SELECT id, name, email, role FROM users");
        return view("admin.manajemen-role.listrole", [
            "title" => "Manajemen Role",
            "users" => $users,
        ]);
    }

    public function edit($id)
    {
        $user = DB::selectOne(
            "SELECT id, name, email, role FROM users WHERE id = ? LIMIT 1",
            [$id],
        );
        return view("admin.manajemen-role.editrole", [
            "title" => "Manajemen Role",
            "user" => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::update("UPDATE users SET role = ? WHERE id = ?", [
            $request->role,
            $id,
        ]);
        return redirect("/admin/list")->with(
            "success",
            "Edit Role pengguna berhasil diperbarui.",
        );
    }

    /**
     * Switch the current authenticated user's role to 'pegawai'.
     */
    public function switchToPegawai(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, "Unauthorized");
        }

        if ($user->getCurrentRole() === "pegawai") {
            return redirect("/")->with(
                "success",
                "Anda sudah berperan sebagai Pegawai.",
            );
        }

        $user->active_role = "pegawai";
        $user->save();

        return redirect("/")->with(
            "success",
            "Role berhasil diubah ke Pegawai.",
        );
    }
}
