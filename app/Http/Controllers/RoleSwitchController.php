<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    /**
     * Switch user to a different role
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            "role" => "required|string|in:admin,perencana,pjk,pegawai",
        ]);

        /** @var User $user */
        $user = Auth::user();
        $newRole = $request->input("role");

        if ($user->canSwitchToRole($newRole)) {
            if ($user->switchRole($newRole)) {
                return redirect("/")->with(
                    "success",
                    "Role berhasil diubah ke: " .
                        $user->getRoleInfo($newRole)["name"],
                );
            } else {
                return redirect("/")->with(
                    "error",
                    "Terjadi kesalahan saat menyimpan perubahan role.",
                );
            }
        } else {
            return redirect("/")->with(
                "error",
                "Anda tidak dapat beralih ke role tersebut atau sudah menggunakan role yang sama.",
            );
        }
    }

    /**
     * Switch user back to their original role
     *
     * @return RedirectResponse
     */
    public function switchToOriginal(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isUsingSwitchedRole()) {
            return redirect("/")->with(
                "info",
                "Anda sudah menggunakan role asli.",
            );
        }

        $originalRole = $user->getOriginalRole();

        if ($user->resetToOriginalRole()) {
            $roleInfo = $user->getRoleInfo($originalRole);
            return redirect("/")->with(
                "success",
                "Role dikembalikan ke: " . $roleInfo["name"],
            );
        } else {
            return redirect("/")->with(
                "error",
                "Terjadi kesalahan saat mengembalikan role.",
            );
        }
    }

    /**
     * Quick switch to a specific role
     *
     * @param string $role
     * @return RedirectResponse
     */
    public function quickSwitch(string $role): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->canSwitchToRole($role)) {
            return redirect("/")->with(
                "error",
                "Anda tidak dapat beralih ke role tersebut atau sudah menggunakan role yang sama.",
            );
        }

        if ($user->switchRole($role)) {
            $roleInfo = $user->getRoleInfo($role);
            return redirect("/")->with(
                "success",
                "Role berhasil diubah ke: " . $roleInfo["name"],
            );
        }

        return redirect("/")->with(
            "error",
            "Terjadi kesalahan saat mengubah role.",
        );
    }
}
