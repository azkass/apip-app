<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class InspekturUtama
{
    protected $table = "inspektur_utama";

    public static function getAll()
    {
        return DB::select(
            "SELECT * FROM inspektur_utama ORDER BY created_at DESC",
        );
    }

    public static function getNama()
    {
        return DB::select(
            "SELECT id, nama FROM inspektur_utama ORDER BY created_at DESC",
        );
    }

    public static function find($id)
    {
        return DB::selectOne(
            "SELECT * FROM inspektur_utama WHERE id = ? LIMIT 1",
            [$id],
        );
    }

    public static function create($data)
    {
        return DB::insert(
            "INSERT INTO inspektur_utama (nama, nip, jabatan, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$data["nama"], $data["nip"], $data["jabatan"]],
        );
    }

    public static function update($id, $data)
    {
        return DB::update(
            "UPDATE inspektur_utama SET nama = ?, nip = ?, jabatan = ?, updated_at = NOW() WHERE id = ?",
            [$data["nama"], $data["nip"], $data["jabatan"], $id],
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM inspektur_utama WHERE id = ?", [$id]);
    }

    public static function findByNip($nip)
    {
        return DB::selectOne(
            "SELECT * FROM inspektur_utama WHERE nip = ? LIMIT 1",
            [$nip],
        );
    }
}
