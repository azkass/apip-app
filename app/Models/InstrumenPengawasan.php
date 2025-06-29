<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class InstrumenPengawasan
{
    protected $table = "instrumen_pengawasan";

    public static function getByStatus($status = null)
    {
        $query = 'SELECT instrumen_pengawasan.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM instrumen_pengawasan
            INNER JOIN users u1 ON instrumen_pengawasan.pengelola_id = u1.id
            INNER JOIN users u2 ON instrumen_pengawasan.pembuat_id = u2.id';

        // Filter berdasarkan status jika diberikan
        if ($status) {
            if ($status === "semua") {
                // Tidak perlu menambahkan filter status
            } else {
                $query .= " WHERE instrumen_pengawasan.status = '$status'";
            }
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
            SELECT instrumen_pengawasan.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM instrumen_pengawasan
            INNER JOIN users u1 ON instrumen_pengawasan.pengelola_id = u1.id
            INNER JOIN users u2 ON instrumen_pengawasan.pembuat_id = u2.id
            WHERE instrumen_pengawasan.id = ?",
            [$id]
        );
    }

    public static function create($data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::insert(
            "INSERT INTO instrumen_pengawasan (judul, pengelola_id, deskripsi, file, status, pembuat_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["judul"],
                $data["pengelola_id"],
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
            "UPDATE instrumen_pengawasan SET judul = ?, pengelola_id = ?, deskripsi = ?, file = ?, status = ?, pembuat_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["judul"],
                $data["pengelola_id"],
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
