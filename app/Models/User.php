<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "role",
        "active_role",
        "password",
        "google_id",
        "google_token",
        "google_refresh_token",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    /**
     * Get all available roles in the system
     *
     * @return array
     */
    public static function getAllRoles(): array
    {
        return ["admin", "perencana", "pjk", "pegawai"];
    }

    /**
     * Get available roles for this user (now includes all roles)
     *
     * @return array
     */
    public function getAvailableRoles(): array
    {
        // User bisa switch ke semua role
        return self::getAllRoles();
    }

    /**
     * Get current active role
     *
     * @return string
     */
    public function getCurrentRole(): string
    {
        return $this->active_role ?? $this->role;
    }

    /**
     * Switch to a different role
     *
     * @param string $role
     * @return bool
     */
    public function switchRole(string $role): bool
    {
        // Validasi apakah bisa switch ke role tersebut
        if (!$this->canSwitchToRole($role)) {
            return false;
        }

        // Jika switch ke role asli, set active_role ke null
        if ($role === $this->getOriginalRole()) {
            return $this->resetToOriginalRole();
        }

        // Set active_role ke role baru
        $this->active_role = $role;
        return $this->save();
    }

    /**
     * Get role display name with icon
     *
     * @param string $role
     * @return array
     */
    public static function getRoleInfo(string $role): array
    {
        $roles = [
            "admin" => [
                "name" => "Administrator",
                "icon" => "fa-user-shield",
                "color" => "text-red-600",
            ],
            "perencana" => [
                "name" => "Perencana",
                "icon" => "fa-user-cog",
                "color" => "text-blue-600",
            ],
            "pjk" => [
                "name" => "Penanggung Jawab",
                "icon" => "fa-user-tie",
                "color" => "text-green-600",
            ],
            "pegawai" => [
                "name" => "Pegawai",
                "icon" => "fa-user",
                "color" => "text-gray-600",
            ],
        ];

        return $roles[$role] ?? [
            "name" => ucfirst($role),
            "icon" => "fa-user",
            "color" => "text-gray-600",
        ];
    }

    /**
     * Check if user can access role switching
     *
     * @return bool
     */
    public function canSwitchRoles(): bool
    {
        // Semua user yang login bisa switch role
        return true;
    }

    /**
     * Get the original role (role asli yang tidak boleh berubah)
     *
     * @return string
     */
    public function getOriginalRole(): string
    {
        return $this->role;
    }

    /**
     * Reset active role to original role
     *
     * @return bool
     */
    public function resetToOriginalRole(): bool
    {
        $this->active_role = null;
        return $this->save();
    }

    /**
     * Check if user is currently using switched role
     *
     * @return bool
     */
    public function isUsingSwitchedRole(): bool
    {
        return !is_null($this->active_role);
    }

    /**
     * Validate that role switching is allowed
     *
     * @param string $newRole
     * @return bool
     */
    public function canSwitchToRole(string $newRole): bool
    {
        // Tidak boleh switch ke role yang sama dengan yang sedang aktif
        if ($newRole === $this->getCurrentRole()) {
            return false;
        }

        // Harus role yang valid
        return in_array($newRole, self::getAllRoles());
    }

    /**
     * Override save to protect original role column
     * Role asli tidak boleh berubah setelah user dibuat
     */
    public function save(array $options = []): bool
    {
        // Jika ini bukan user baru dan ada perubahan di kolom role
        if (!$this->wasRecentlyCreated && $this->isDirty("role")) {
            // Kembalikan role ke nilai asli
            $this->role = $this->getOriginal("role");

            // Log warning atau throw exception jika diperlukan
            Log::warning(
                "Attempted to change original role for user ID: " . $this->id,
            );
        }

        return parent::save($options);
    }
}
