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
            INNER JOIN users u1 ON regulasi.perencana_id = u1.id");
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
            INNER JOIN users u1 ON regulasi.perencana_id = u1.id
            WHERE regulasi.id = ?",
            [$id]
        );
    }

    public static function create($data)
    {
        return DB::insert(
            "INSERT INTO regulasi (judul, tautan, perencana_id, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$data["judul"], $data["tautan"], $data["perencana_id"]]
        );
    }

    public static function update($id, $data)
    {
        return DB::update(
            "UPDATE regulasi SET judul = ?, tautan = ?, perencana_id = ?, updated_at = NOW() WHERE id = ?",
            [$data["judul"], $data["tautan"], $data["perencana_id"], $id]
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM regulasi WHERE id = ?", [$id]);
    }
}
