<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeEvaluasiProsedur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PeriodeEvaluasiProsedurController extends Controller
{
    public function index()
    {
        $periode = PeriodeEvaluasiProsedur::getAll();
        return view("periode.index", [
            "periode" => $periode,
            "title" => "Daftar Periode Evaluasi Prosedur",
        ]);
    }

    public function create()
    {
        return view("periode.create", [
            "title" => "Tambah Periode Evaluasi Prosedur",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "mulai" => "required|date",
            "berakhir" => "required|date|after_or_equal:mulai",
            "pembuat_id" => "required|integer|exists:users,id",
        ]);

        PeriodeEvaluasiProsedur::insertData(
            $request->pembuat_id,
            $request->mulai,
            $request->berakhir
        );

        return redirect()
            ->route("periode.index")
            ->with("success", "Periode berhasil ditambahkan.");
    }

    public function edit()
    {
        $periode = PeriodeEvaluasiProsedur::getLatest();
        return view("periode.edit", [
            "periode" => $periode,
            "title" => "Edit Periode Evaluasi Prosedur",
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            "mulai" => "required|date",
            "berakhir" => "required|date|after_or_equal:mulai",
            "pembuat_id" => "required|integer|exists:users,id",
        ]);

        $periode = PeriodeEvaluasiProsedur::getLatest();
        if ($periode) {
            PeriodeEvaluasiProsedur::updateData(
                $periode->id,
                $request->pembuat_id,
                $request->mulai,
                $request->berakhir
            );
        } else {
            PeriodeEvaluasiProsedur::insertData(
                $request->pembuat_id,
                $request->mulai,
                $request->berakhir
            );
        }

        return back()->with("success", "Periode berhasil diperbarui.");
    }

    public function destroy($id)
    {
        PeriodeEvaluasiProsedur::deleteData($id);
        return redirect()
            ->route("periode.index")
            ->with("success", "Periode berhasil dihapus");
    }
}
