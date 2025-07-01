<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiProsedur;

class EvaluasiProsedurController extends Controller
{
    public function index()
    {
        $data = EvaluasiProsedur::getAll();
        return view("evaluasi.index", [
            "data" => $data,
            "title" => "Daftar Evaluasi Prosedur",
        ]);
    }

    public function create($sop_id)
    {
        return view("evaluasi.create", [
            "sop_id" => $sop_id,
            "title" => "Tambah Evaluasi Prosedur",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "isi" => "required|string",
            "sop_id" => "required|integer|exists:prosedur_pengawasan,id",
        ]);

        EvaluasiProsedur::insertData(
            $request->sop_id,
            $request->judul,
            $request->isi
        );

        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil disimpan!");
    }

    public function show($id)
    {
        $evaluasi = EvaluasiProsedur::findById($id);
        return view("evaluasi.show", [
            "evaluasi" => $evaluasi,
            "title" => "Detail Evaluasi Prosedur",
        ]);
    }

    public function edit($id)
    {
        $evaluasi = EvaluasiProsedur::findById($id);
        return view("evaluasi.edit", [
            "evaluasi" => $evaluasi,
            "title" => "Edit Evaluasi Prosedur",
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "isi" => "required|string",
            "sop_id" => "required|integer|exists:prosedur_pengawasan,id",
        ]);

        EvaluasiProsedur::updateData($id, $request->judul, $request->isi);
        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil diupdate");
    }

    public function destroy($id)
    {
        EvaluasiProsedur::deleteData($id);
        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil dihapus");
    }
}
