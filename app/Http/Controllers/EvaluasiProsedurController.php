<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiProsedur;

class EvaluasiProsedurController extends Controller
{
    public function index()
    {
        $data = EvaluasiProsedur::getAll();
        return view("evaluasi.index", compact("data"));
    }

    public function create($sop_id)
    {
        return view("evaluasi.create", compact("sop_id"));
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
        return view("evaluasi.show", compact("evaluasi"));
    }

    public function edit($id)
    {
        $evaluasi = EvaluasiProsedur::findById($id);
        return view("evaluasi.edit", compact("evaluasi"));
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
