<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Regulasi
{
    protected $table = "regulasi";

    public static function getAll()
    {
        return DB::select("SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id");
    }

    public static function find($id)
    {
        return DB::selectOne("SELECT * FROM regulasi WHERE id = ?", [$id]);
    }

    public static function detail($id)
    {
        return DB::selectOne(
            "
            SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.id = ?",
            [$id]
        );
    }

    public static function create($data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::insert(
            "INSERT INTO regulasi (judul, tautan, file, pembuat_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
            [
                $data["judul"],
                $data["tautan"],
                $data["file"],
                $data["pembuat_id"],
            ]
        );
    }

    public static function update($id, $data)
    {
        // Mengubah judul menjadi title case sebelum insert
        $data["judul"] = ucwords(strtolower($data["judul"]));
        return DB::update(
            "UPDATE regulasi SET judul = ?, tautan = ?, file = ?, pembuat_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["judul"],
                $data["tautan"],
                $data["file"],
                $data["pembuat_id"],
                $id,
            ]
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM regulasi WHERE id = ?", [$id]);
    }
}
