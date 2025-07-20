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
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            ORDER BY regulasi.tahun DESC, regulasi.nomor ASC");
    }

    public static function find($id)
    {
        return DB::selectOne("SELECT * FROM regulasi WHERE id = ?", [$id]);
    }

    public static function findPdf($id)
    {
        return DB::selectOne(
            "SELECT id, file FROM regulasi WHERE id = ? LIMIT 1",
            [$id],
        );
    }

    public static function detail($id)
    {
        return DB::selectOne(
            "
            SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.id = ?",
            [$id],
        );
    }

    public static function create($data)
    {
        return DB::insert(
            "INSERT INTO regulasi (tahun, nomor, tentang, jenis_peraturan, status, tautan, file, pembuat_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["tahun"],
                $data["nomor"],
                $data["tentang"],
                $data["jenis_peraturan"],
                $data["status"],
                $data["tautan"],
                $data["file"],
                $data["pembuat_id"],
            ],
        );
    }

    public static function update($id, $data)
    {
        return DB::update(
            "UPDATE regulasi SET tahun = ?, nomor = ?, tentang = ?, jenis_peraturan = ?, status = ?, tautan = ?, file = ?, pembuat_id = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["tahun"],
                $data["nomor"],
                $data["tentang"],
                $data["jenis_peraturan"],
                $data["status"],
                $data["tautan"],
                $data["file"],
                $data["pembuat_id"],
                $id,
            ],
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM regulasi WHERE id = ?", [$id]);
    }

    public static function getByStatus($status)
    {
        return DB::select(
            "SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.status = ?
            ORDER BY regulasi.tahun DESC, regulasi.nomor ASC",
            [$status],
        );
    }

    public static function getByJenisPeraturan($jenisPeraturan)
    {
        return DB::select(
            "SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.jenis_peraturan = ?
            ORDER BY regulasi.tahun DESC, regulasi.nomor ASC",
            [$jenisPeraturan],
        );
    }

    public static function getByJenisPeraturanAndStatus(
        $jenisPeraturan,
        $status,
    ) {
        return DB::select(
            "SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.jenis_peraturan = ? AND regulasi.status = ?
            ORDER BY regulasi.tahun DESC, regulasi.nomor ASC",
            [$jenisPeraturan, $status],
        );
    }

    public static function search($keyword)
    {
        return DB::select(
            "SELECT regulasi.*, u1.name AS perencana_nama
            FROM regulasi
            INNER JOIN users u1 ON regulasi.pembuat_id = u1.id
            WHERE regulasi.tentang LIKE ? OR regulasi.tahun LIKE ? OR regulasi.nomor LIKE ?
            ORDER BY regulasi.tahun DESC, regulasi.nomor ASC",
            ["%{$keyword}%", "%{$keyword}%", "%{$keyword}%"],
        );
    }
}
