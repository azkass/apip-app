<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class InstrumenPengawasan
{
    protected $table = "instrumen_pengawasan";

    public static function getAll()
    {
        return DB::select('SELECT instrumen_pengawasan.*, u1.name AS petugas_nama, u2.name AS perencana_nama
            FROM instrumen_pengawasan
            INNER JOIN users u1 ON instrumen_pengawasan.petugas_pengelola_id = u1.id
            INNER JOIN users u2 ON instrumen_pengawasan.perencana_id = u2.id');
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
            INNER JOIN users u1 ON instrumen_pengawasan.petugas_pengelola_id = u1.id
            INNER JOIN users u2 ON instrumen_pengawasan.perencana_id = u2.id
            WHERE instrumen_pengawasan.id = ?",
            [$id]
        );
    }

    public static function create($data)
    {
        return DB::insert(
            "INSERT INTO instrumen_pengawasan (judul, petugas_pengelola_id, isi, status, perencana_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["judul"],
                $data["petugas_pengelola_id"],
                $data["isi"],
                $data["status"],
                $data["perencana_id"],
            ]
        );
    }

    public static function update($id, $data)
    {
        return DB::update(
            "UPDATE instrumen_pengawasan SET judul = ?, petugas_pengelola_id = ?, isi = ?, status = ?, perencana_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["judul"],
                $data["petugas_pengelola_id"],
                $data["isi"],
                $data["status"],
                $data["perencana_id"],
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
