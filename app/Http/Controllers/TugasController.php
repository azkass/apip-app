<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProsedurPengawasan;
use App\Models\InstrumenPengawasan;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        // $status = $request->query("status", "semua");
        // $activeTab = $status;
        $prosedurPengawasan = ProsedurPengawasan::getByPetugas(
            Auth::user()->id,
        );
        $instrumenPengawasan = InstrumenPengawasan::getByPetugas(
            Auth::user()->id,
        );

        $viewData = [
            "title" => "Tugas Saya",
            // "activeTab" => $activeTab,
            "prosedurPengawasan" => $prosedurPengawasan,
            "instrumenPengawasan" => $instrumenPengawasan,
        ];

        return view("tugas.index", $viewData);
    }
}
