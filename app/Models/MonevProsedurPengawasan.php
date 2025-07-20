<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MonevProsedurPengawasan
{
    protected $table = "monev_prosedur_pengawasan";
    public static function index()
    {
        return DB::select("
            SELECT me.*, pp.nomor as sop_nomor,
            pp.nama as sop_nama
            FROM monev_prosedur_pengawasan me
            JOIN prosedur_pengawasan pp ON me.sop_id = pp.id
        ");
    }
    public static function allWithProsedurPengawasan()
    {
        return DB::select("
            SELECT m.*, p.*
            FROM monev_prosedur_pengawasan m
            JOIN prosedur_pengawasan p ON m.sop_id = p.id
        ");
    }

    public static function findWithProsedurPengawasan($id)
    {
        $results = DB::select(
            "
            SELECT mp.*, pp.nama as sop_nama, pp.nomor as sop_nomor
            FROM monev_prosedur_pengawasan mp
            JOIN prosedur_pengawasan pp ON mp.sop_id = pp.id
            WHERE mp.id = ?
        ",
            [$id],
        );

        return $results[0] ?? null;
    }

    public static function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO monev_prosedur_pengawasan ($columns) VALUES ($placeholders)";

        DB::insert($sql, array_values($data));

        return DB::getPdo()->lastInsertId();
    }

    public static function monevExists($sop_id)
    {
        $result = DB::selectOne(
            "SELECT COUNT(*) as count FROM monev_prosedur_pengawasan WHERE sop_id = ?",
            [$sop_id],
        );
        return $result->count > 0;
    }

    public static function update($id, array $data)
    {
        $setClause = implode(
            ", ",
            array_map(function ($key) {
                return "$key = ?";
            }, array_keys($data)),
        );

        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE monev_prosedur_pengawasan SET $setClause WHERE id = ?";

        return DB::update($sql, $values);
    }

    public static function delete($id)
    {
        return DB::delete(
            "DELETE FROM monev_prosedur_pengawasan WHERE id = ?",
            [$id],
        );
    }

    public static function find($id)
    {
        $results = DB::selectOne(
            "SELECT * FROM monev_prosedur_pengawasan WHERE id = ? LIMIT 1",
            [$id],
        );
        return $results ?? null;
    }

    public static function getGroupedData()
    {
        return DB::select("
            SELECT
                mpp.id,
                mpp.sop_id,
                pp.nomor AS sop_nomor,
                pp.nama AS sop_nama,
                u.name AS penyusun_nama,
                7 AS jumlah_pertanyaan, -- There are 7 question columns
                (
                    (CASE WHEN mpp.mampu_mendorong_kinerja = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.mampu_dipahami = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.mudah_dilaksanakan = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.dapat_menjalankan_peran = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.mampu_mengatasi_permasalahan = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.mampu_menjawab_kebutuhan = 'ya' THEN 1 ELSE 0 END) +
                    (CASE WHEN mpp.sinergi_dengan_lainnya = 'ya' THEN 1 ELSE 0 END)
                ) AS jawaban_ya
            FROM
                monev_prosedur_pengawasan mpp
            JOIN
                prosedur_pengawasan pp ON mpp.sop_id = pp.id
            JOIN
                users u ON pp.penyusun_id = u.id
            ORDER BY
                mpp.created_at DESC
        ");
    }
}
