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
                u.name as penyusun_nama
            FROM evaluasi_prosedur ep
            JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
            JOIN users u ON pp.penyusun_id = u.id
            ORDER BY ep.id DESC
        ");
    }

    public static function findById($id)
    {
        return DB::selectOne(
            "
            SELECT
                ep.id,
                ep.sop_id,
                ep.judul,
                ep.isi,
                ep.created_at,
                ep.updated_at,
                pp.nomor as sop_nomor,
                pp.judul as sop_judul
            FROM evaluasi_prosedur ep
            JOIN prosedur_pengawasan pp ON ep.sop_id = pp.id
            WHERE ep.id = ?
        ",
            [$id]
        );
    }

    public static function insertData($sop_id, $judul, $isi)
    {
        return DB::insert(
            "INSERT INTO evaluasi_prosedur (sop_id, judul, isi, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$sop_id, $judul, $isi]
        );
    }

    public static function updateData($id, $judul, $isi)
    {
        return DB::update(
            "UPDATE evaluasi_prosedur SET judul = ?, isi = ?, updated_at = NOW() WHERE id = ?",
            [$judul, $isi, $id]
        );
    }

    public static function deleteData($id)
    {
        return DB::delete("DELETE FROM evaluasi_prosedur WHERE id = ?", [$id]);
    }
}
