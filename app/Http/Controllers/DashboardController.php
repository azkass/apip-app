<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get users by role
        $userRoles = DB::select(
            "SELECT role, COUNT(*) as count FROM users GROUP BY role",
        );
        $roleLabels = [];
        $roleCounts = [];
        $totalUsers = 0;

        foreach ($userRoles as $role) {
            // Format the role label, changing "pjk" to "Penanggung Jawab"
            if ($role->role == "pjk") {
                $roleLabels[] = "Penanggung Jawab Kegiatan";
            } else {
                $roleLabels[] = ucfirst($role->role);
            }
            $roleCounts[] = $role->count;
            $totalUsers += $role->count;
        }

        // Get procedure status counts for current year
        $currentYear = date("Y");
        $procedureStatuses = DB::select(
            "
            SELECT status, COUNT(*) as count
            FROM prosedur_pengawasan
            WHERE YEAR(created_at) = ?
            GROUP BY status
        ",
            [$currentYear],
        );

        $statusLabels = [];
        $statusCounts = [];
        $totalProcedures = 0;

        foreach ($procedureStatuses as $status) {
            $statusLabels[] = ucfirst($status->status);
            $statusCounts[] = $status->count;
            $totalProcedures += $status->count;
        }

        $data = [
            "title" => "Dashboard",
            "roleLabels" => $roleLabels,
            "roleCounts" => $roleCounts,
            "totalUsers" => $totalUsers,
            "statusLabels" => $statusLabels,
            "statusCounts" => $statusCounts,
            "totalProcedures" => $totalProcedures,
            "currentYear" => $currentYear,
            "userRoles" => $userRoles,
            "procedureStatuses" => $procedureStatuses,
        ];

        // Determine which view to render based on user role
        $userRole = Auth::user()->role;

        return view("dashboard", $data);
    }
}
