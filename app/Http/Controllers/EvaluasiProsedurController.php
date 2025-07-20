<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiProsedur;
use App\Models\PertanyaanEvaluasi;

class EvaluasiProsedurController extends Controller
{
    public function index()
    {
        $groupedData = EvaluasiProsedur::getGroupedBySop();
        return view("evaluasi.index", [
            "groupedData" => $groupedData,
            "title" => "Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function create($sop_id)
    {
        // Check if evaluasi already exists for this SOP
        if (EvaluasiProsedur::evaluasiExists($sop_id)) {
            return redirect()
                ->route("evaluasi.index")
                ->with("error", "Evaluasi untuk SOP ini sudah ada!");
        }

        // Get all pertanyaan evaluasi
        $pertanyaan = PertanyaanEvaluasi::index();

        if (empty($pertanyaan)) {
            return redirect()
                ->route("evaluasi.index")
                ->with(
                    "error",
                    "Tidak ada pertanyaan evaluasi! Tambahkan pertanyaan terlebih dahulu.",
                );
        }

        return view("evaluasi.create", [
            "sop_id" => $sop_id,
            "pertanyaan" => $pertanyaan,
            "title" => "Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "sop_id" => "required|integer|exists:prosedur_pengawasan,id",
            "jawaban" => "required|array",
            "pertanyaan_id" => "required|array",
        ]);

        $sop_id = $request->sop_id;
        $jawaban = $request->jawaban;
        $pertanyaan_ids = $request->pertanyaan_id;

        // Delete any existing evaluations for this SOP
        EvaluasiProsedur::deleteDataBySopId($sop_id);

        // Insert new evaluations
        foreach ($pertanyaan_ids as $index => $pertanyaan_id) {
            $jawabanValue = isset($jawaban[$pertanyaan_id])
                ? (int) $jawaban[$pertanyaan_id]
                : 0;
            EvaluasiProsedur::insertData(
                $sop_id,
                $pertanyaan_id,
                $jawabanValue,
            );
        }

        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil disimpan!");
    }

    public function show($sop_id)
    {
        $evaluasiItems = EvaluasiProsedur::findBySopId($sop_id);

        if (empty($evaluasiItems)) {
            return redirect()
                ->route("evaluasi.index")
                ->with("error", "Data evaluasi tidak ditemukan!");
        }

        // Count totals and scores
        $totalPertanyaan = count($evaluasiItems);
        $jawabanYa = 0;

        foreach ($evaluasiItems as $item) {
            if ($item->jawaban == 1) {
                $jawabanYa++;
            }
        }

        $persentase =
            $totalPertanyaan > 0 ? ($jawabanYa / $totalPertanyaan) * 100 : 0;

        $catatan = $evaluasiItems[0]->catatan ?? null;
        $penilaian = $evaluasiItems[0]->penilaian ?? null;
        $tindakan = $evaluasiItems[0]->tindakan ?? null;
        return view("evaluasi.show", [
            "evaluasiItems" => $evaluasiItems,
            "totalPertanyaan" => $totalPertanyaan,
            "jawabanYa" => $jawabanYa,
            "persentase" => $persentase,
            "sop_id" => $sop_id,
            "sop_nomor" => $evaluasiItems[0]->sop_nomor ?? "",
            "sop_nama" => $evaluasiItems[0]->sop_nama ?? "",
            "catatan" => $catatan,
            "penilaian" => $penilaian,
            "tindakan" => $tindakan,
            "title" => "Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function edit($sop_id)
    {
        $evaluasiItems = EvaluasiProsedur::findBySopId($sop_id);

        if (empty($evaluasiItems)) {
            return redirect()
                ->route("evaluasi.index")
                ->with("error", "Data evaluasi tidak ditemukan!");
        }

        // Get all pertanyaan evaluasi
        $allPertanyaan = PertanyaanEvaluasi::index();

        // Create a map of pertanyaan_id => jawaban
        $jawabanMap = [];
        foreach ($evaluasiItems as $item) {
            $jawabanMap[$item->pertanyaan_id] = $item->jawaban;
        }

        return view("evaluasi.edit", [
            "evaluasiItems" => $evaluasiItems,
            "allPertanyaan" => $allPertanyaan,
            "jawabanMap" => $jawabanMap,
            "sop_id" => $sop_id,
            "sop_nomor" => $evaluasiItems[0]->sop_nomor ?? "",
            "sop_nama" => $evaluasiItems[0]->sop_nama ?? "",
            "title" => "Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function update(Request $request, $sop_id)
    {
        $request->validate([
            "jawaban" => "required|array",
            "pertanyaan_id" => "required|array",
        ]);

        $jawaban = $request->jawaban;
        $pertanyaan_ids = $request->pertanyaan_id;

        // Delete existing evaluations for this SOP
        EvaluasiProsedur::deleteDataBySopId($sop_id);

        // Insert updated evaluations
        foreach ($pertanyaan_ids as $index => $pertanyaan_id) {
            $jawabanValue = isset($jawaban[$pertanyaan_id])
                ? (int) $jawaban[$pertanyaan_id]
                : 0;
            EvaluasiProsedur::insertData(
                $sop_id,
                $pertanyaan_id,
                $jawabanValue,
            );
        }

        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil diupdate");
    }

    public function destroy($sop_id)
    {
        EvaluasiProsedur::deleteDataBySopId($sop_id);
        return redirect()
            ->route("evaluasi.index")
            ->with("success", "Evaluasi berhasil dihapus");
    }
}
