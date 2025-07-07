<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EvaluasiProsedur extends Model
{
    protected $table = "evaluasi_prosedur";

    public static function getAll()
{
    return DB::select("
        SELECT
            ep.id,
            ep.sop_id,
            ep.created_at,
            ep.updated_at,
            pp.nomor as sop_nomor,
            pp.judul as sop_judul,
            pp.penyusun_id as penyusun_id,
            u.name as penyusun_nama,
            COUNT(DISTINCT ep.pertanyaan_id) as jumlah_pertanyaan,
            SUM(CASE WHEN ep.jawaban = 1 THEN 1 ELSE 0 END) as jawaban_ya
        FROM evaluasi_prosedur ep
        JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
        JOIN users u ON pp.penyusun_id = u.id
        GROUP BY 
            ep.id,
            ep.sop_id, 
            ep.created_at,
            ep.updated_at,
            pp.nomor,
            pp.judul,
            pp.penyusun_id,
            u.name
        ORDER BY ep.created_at DESC
    ");
}

    public static function findBySopId($sop_id)
    {
        return DB::select("
            SELECT
                ep.id,
                ep.sop_id,
                ep.pertanyaan_id,
                ep.jawaban,
                ep.created_at,
                pe.pertanyaan,
                pp.nomor as sop_nomor,
                pp.judul as sop_judul
            FROM evaluasi_prosedur ep
            JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
            JOIN pertanyaan_evaluasi pe ON ep.pertanyaan_id = pe.id
            WHERE ep.sop_id = ?
            ORDER BY pe.id ASC
        ", [$sop_id]);
    }

    public static function findById($id)
    {
        return DB::selectOne("
            SELECT
                ep.id,
                ep.sop_id,
                ep.pertanyaan_id,
                ep.jawaban,
                pe.pertanyaan,
                pp.nomor as sop_nomor,
                pp.judul as sop_judul
            FROM evaluasi_prosedur ep
            JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
            JOIN pertanyaan_evaluasi pe ON ep.pertanyaan_id = pe.id
            WHERE ep.id = ?
        ", [$id]);
    }

    public static function insertData($sop_id, $pertanyaan_id, $jawaban)
    {
        return DB::insert(
            "INSERT INTO evaluasi_prosedur (sop_id, pertanyaan_id, jawaban, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$sop_id, $pertanyaan_id, $jawaban]
        );
    }

    public static function updateData($id, $jawaban)
    {
        return DB::update(
            "UPDATE evaluasi_prosedur SET jawaban = ?, updated_at = NOW() WHERE id = ?",
            [$jawaban, $id]
        );
    }

    public static function deleteDataBySopId($sop_id)
    {
        return DB::delete("DELETE FROM evaluasi_prosedur WHERE sop_id = ?", [$sop_id]);
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM evaluasi_prosedur WHERE id = ?", [$id]);
    }
    
    // Check if evaluation exists for a SOP
    public static function evaluasiExists($sop_id)
    {
        $result = DB::selectOne("SELECT COUNT(*) as count FROM evaluasi_prosedur WHERE sop_id = ?", [$sop_id]);
        return $result->count > 0;
    }

    public static function getGroupedBySop()
    {
        return DB::select("
            SELECT
                ep.sop_id,
                MAX(ep.created_at) as created_at,
                MAX(ep.updated_at) as updated_at,
                pp.nomor as sop_nomor,
                pp.judul as sop_judul,
                pp.penyusun_id as penyusun_id,
                u.name as penyusun_nama,
                COUNT(DISTINCT ep.pertanyaan_id) as jumlah_pertanyaan,
                SUM(CASE WHEN ep.jawaban = 1 THEN 1 ELSE 0 END) as jawaban_ya
            FROM evaluasi_prosedur ep
            JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
            JOIN users u ON pp.penyusun_id = u.id
            GROUP BY ep.sop_id, pp.nomor, pp.judul, pp.penyusun_id, u.name
            ORDER BY MAX(ep.created_at) DESC
        ");
    }
}
