<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class InstrumenPengawasan
{
    protected $table = "instrumen_pengawasan";

    public static function getByStatus($status = null)
    {
        $query = 'SELECT ip.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM instrumen_pengawasan ip
            INNER JOIN users u1 ON ip.penyusun_id = u1.id
            INNER JOIN users u2 ON ip.pembuat_id = u2.id';

        // Filter berdasarkan status jika diberikan
        if ($status && $status !== "semua") {
            $query .= " WHERE ip.status = '" . addslashes($status) . "'";
        }
        return DB::select($query);
    }

    public static function find($id)
    {
        return DB::selectOne(
            "SELECT * FROM instrumen_pengawasan WHERE id = ?",
            [$id]
        );
    }

    public static function detail($id)
    {
        return DB::selectOne(
            "
            SELECT ip.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM instrumen_pengawasan ip
            INNER JOIN users u1 ON ip.penyusun_id = u1.id
            INNER JOIN users u2 ON ip.pembuat_id = u2.id
            WHERE ip.id = ?",
            [$id]
        );
    }

    public static function create($data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::insert(
            "INSERT INTO instrumen_pengawasan (judul, penyusun_id, deskripsi, file, status, pembuat_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["judul"],
                $data["penyusun_id"],
                $data["deskripsi"],
                $data["file"],
                $data["status"],
                $data["pembuat_id"],
            ]
        );
    }

    public static function update($id, $data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::update(
            "UPDATE instrumen_pengawasan SET judul = ?, penyusun_id = ?, deskripsi = ?, file = ?, status = ?, pembuat_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["judul"],
                $data["penyusun_id"],
                $data["deskripsi"],
                $data["file"],
                $data["status"],
                $data["pembuat_id"],
                $id,
            ]
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM instrumen_pengawasan WHERE id = ?", [
            $id,
        ]);
    }
}
