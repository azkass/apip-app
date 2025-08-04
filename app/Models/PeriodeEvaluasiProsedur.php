<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PeriodeEvaluasiProsedur extends Model
{
    protected $table = "periode_evaluasi_prosedur";

    public static function getAll()
    {
        return DB::select("
            SELECT
                pep.id,
                pep.pembuat_id,
                pep.mulai,
                pep.berakhir,
                pep.created_at,
                pep.updated_at,
                u.name as pembuat_name,
                u.email as pembuat_email
            FROM periode_evaluasi_prosedur pep
            JOIN users u ON pep.pembuat_id = u.id
            ORDER BY pep.created_at DESC
        ");
    }

    public static function findById($id)
    {
        return DB::selectOne(
            "
            SELECT
                pep.id,
                pep.pembuat_id,
                pep.mulai,
                pep.berakhir,
                pep.created_at,
                pep.updated_at,
                u.name as pembuat_name,
                u.email as pembuat_email
            FROM periode_evaluasi_prosedur pep
            JOIN users u ON pep.pembuat_id = u.id
            WHERE pep.id = ?
            LIMIT 1
        ",
            [$id],
        );
    }

    public static function insertData($pembuat_id, $mulai, $berakhir)
    {
        return DB::insert(
            "INSERT INTO periode_evaluasi_prosedur (pembuat_id, mulai, berakhir, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$pembuat_id, $mulai, $berakhir],
        );
    }

    public static function updateData($id, $pembuat_id, $mulai, $berakhir)
    {
        return DB::update(
            "UPDATE periode_evaluasi_prosedur SET pembuat_id = ?, mulai = ?, berakhir = ?, updated_at = NOW() WHERE id = ?",
            [$pembuat_id, $mulai, $berakhir, $id],
        );
    }

    public static function deleteData($id)
    {
        return DB::delete(
            "DELETE FROM periode_evaluasi_prosedur WHERE id = ?",
            [$id],
        );
    }

    public static function getLatest()
    {
        return DB::selectOne("
            SELECT
                pep.id,
                pep.pembuat_id,
                pep.mulai,
                pep.berakhir,
                pep.created_at,
                pep.updated_at,
                u.name as pembuat_name,
                u.email as pembuat_email
            FROM periode_evaluasi_prosedur pep
            JOIN users u ON pep.pembuat_id = u.id
            ORDER BY pep.created_at DESC
            LIMIT 1
        ");
    }

    public static function getLatestSimple()
    {
        return DB::selectOne("
            SELECT
                id,
                pembuat_id,
                mulai,
                berakhir,
                created_at,
                updated_at
            FROM periode_evaluasi_prosedur
            ORDER BY created_at DESC
            LIMIT 1
        ");
    }

    public static function getActivePeriode()
    {
        $today = date("Y-m-d");
        return DB::selectOne(
            "
            SELECT
                pep.id,
                pep.pembuat_id,
                pep.mulai,
                pep.berakhir,
                pep.created_at,
                pep.updated_at,
                u.name as pembuat_name,
                u.email as pembuat_email
            FROM periode_evaluasi_prosedur pep
            JOIN users u ON pep.pembuat_id = u.id
            WHERE ? BETWEEN pep.mulai AND pep.berakhir
            ORDER BY pep.created_at DESC
            LIMIT 1
        ",
            [$today],
        );
    }

    public static function isPeriodeActive()
    {
        $today = date("Y-m-d");
        $periode = DB::selectOne(
            "
            SELECT id FROM periode_evaluasi_prosedur
            WHERE ? BETWEEN mulai AND berakhir
            LIMIT 1
        ",
            [$today],
        );

        return $periode !== null;
    }

    public static function count()
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as total FROM periode_evaluasi_prosedur",
        );
        return $result->total;
    }
}
