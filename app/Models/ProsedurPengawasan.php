<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ProsedurPengawasan
{
    protected $table = "prosedur_pengawasan";

    public static function all()
    {
        return DB::select("SELECT * FROM prosedur_pengawasan");
    }

    public static function getByStatus($status = null)
    {
        $query = 'SELECT prosedur_pengawasan.id, prosedur_pengawasan.nomor, prosedur_pengawasan.judul, prosedur_pengawasan.status, prosedur_pengawasan.updated_at, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM prosedur_pengawasan
            INNER JOIN users u1 ON prosedur_pengawasan.penyusun_id = u1.id
            INNER JOIN users u2 ON prosedur_pengawasan.pembuat_id = u2.id';

        // Filter berdasarkan status jika diberikan
        if ($status) {
            if ($status === "semua") {
                // Tidak perlu menambahkan filter status
            } else {
                $query .= " WHERE prosedur_pengawasan.status = '$status'";
            }
        }
        return DB::select($query);
    }

    public static function create($data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::insert(
            "INSERT INTO prosedur_pengawasan (judul, nomor, status, pembuat_id, penyusun_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["judul"],
                $data["nomor"],
                $data["status"],
                $data["pembuat_id"],
                $data["penyusun_id"],
            ]
        );
    }

    public static function find($id)
    {
        return DB::selectOne("SELECT * FROM prosedur_pengawasan WHERE id = ?", [
            $id,
        ]);
    }

    public static function update($id, $data)
    {
        // Mengubah judul menjadi title case sebelum update
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::update(
            "UPDATE prosedur_pengawasan SET judul = ?, nomor = ?, status = ?, pembuat_id = ?, penyusun_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["judul"],
                $data["nomor"],
                $data["status"],
                $data["pembuat_id"],
                $data["penyusun_id"],
                $id,
            ]
        );
    }

    public static function updateBody($id, $data)
    {
        return DB::update(
            "UPDATE prosedur_pengawasan SET isi = ?, updated_at = NOW() WHERE id = ?",
            [$data["isi"], $id]
        );
    }

    public static function detail($id)
    {
        return DB::selectOne(
            "
            SELECT prosedur_pengawasan.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM prosedur_pengawasan
            INNER JOIN users u1 ON prosedur_pengawasan.penyusun_id = u1.id
            INNER JOIN users u2 ON prosedur_pengawasan.pembuat_id = u2.id
            WHERE prosedur_pengawasan.id = ?",
            [$id]
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM prosedur_pengawasan WHERE id = ?", [
            $id,
        ]);
    }
}
