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
        $query = "SELECT pp.id, pp.nomor, pp.nama, pp.status, pp.updated_at,
                  pp.tanggal_pembuatan, pp.tanggal_revisi, pp.tanggal_efektif,
                  u1.name AS petugas_nama, u2.name AS perencana_nama
                  FROM prosedur_pengawasan pp
                  JOIN users u1 ON pp.penyusun_id = u1.id
                  JOIN users u2 ON pp.pembuat_id = u2.id";

        if ($status && $status !== "semua") {
            $query .= " WHERE pp.status = '" . addslashes($status) . "'";
        }

        return DB::select($query);
    }

    // Untuk Tampilan Tugas Saya
    // Notes: Menggunakan UNION dibanding OR agar performa lebih baik
    public static function getByPetugas($petugasId)
    {
        $query = "
            SELECT pp.id, pp.nomor, pp.nama, pp.status, pp.updated_at,
                   pp.tanggal_pembuatan, pp.tanggal_revisi, pp.tanggal_efektif,
                   u1.name AS penyusun_nama, u2.name AS pembuat_nama
            FROM prosedur_pengawasan pp
            JOIN users u1 ON pp.penyusun_id = u1.id
            JOIN users u2 ON pp.pembuat_id = u2.id
            WHERE pp.pembuat_id = $petugasId

            UNION

            SELECT pp.id, pp.nomor, pp.nama, pp.status, pp.updated_at,
                   pp.tanggal_pembuatan, pp.tanggal_revisi, pp.tanggal_efektif,
                   u1.name AS penyusun_nama, u2.name AS pembuat_nama
            FROM prosedur_pengawasan pp
            JOIN users u1 ON pp.penyusun_id = u1.id
            JOIN users u2 ON pp.pembuat_id = u2.id
            WHERE pp.penyusun_id = $petugasId
        ";
        return DB::select($query);
    }

    public static function create($data)
    {
        // Mengubah nama menjadi title case lalu normalisasi 'SOP' ke kapital
        $data["nama"] = ucwords(strtolower($data["nama"]));
        $data["nama"] = preg_replace("/\bSOP\b/i", "SOP", $data["nama"]);
        // Pastikan nomor selalu kapital
        $data["nomor"] = strtoupper($data["nomor"]);
        return DB::insert(
            "INSERT INTO prosedur_pengawasan (nama, nomor, status, pembuat_id, penyusun_id, tanggal_pembuatan, tanggal_revisi, tanggal_efektif, disahkan_oleh, cover, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $data["nama"],
                $data["nomor"],
                $data["status"],
                $data["pembuat_id"],
                $data["penyusun_id"],
                $data["tanggal_pembuatan"] ?? null,
                $data["tanggal_revisi"] ?? null,
                $data["tanggal_efektif"] ?? null,
                $data["disahkan_oleh"],
                $data["cover"] ?? null,
            ],
        );
    }

    public static function findById($id)
    {
        return DB::selectOne(
            "SELECT id, nomor, nama, status, file_ttd FROM prosedur_pengawasan WHERE id = ? LIMIT 1",
            [$id],
        );
    }

    public static function updateTtd($id, $data)
    {
        return DB::update(
            "UPDATE prosedur_pengawasan SET file_ttd = ?, status = 'disetujui', updated_at = NOW() WHERE id = ?",
            [$data["file_ttd"], $id],
        );
    }

    public static function findHeader($id)
    {
        return DB::selectOne(
            "SELECT id, nomor, nama, tanggal_pembuatan, tanggal_revisi, tanggal_efektif, disahkan_oleh, penyusun_id, status, pembuat_id FROM prosedur_pengawasan WHERE id = ? LIMIT 1",
            [$id],
        );
    }

    public static function update($id, $data)
    {
        // Mengubah nama menjadi title case lalu normalisasi 'SOP' ke kapital
        $data["nama"] = ucwords(strtolower($data["nama"]));
        $data["nama"] = preg_replace("/\bSOP\b/i", "SOP", $data["nama"]);
        // Pastikan nomor selalu kapital
        $data["nomor"] = strtoupper($data["nomor"]);
        return DB::update(
            "UPDATE prosedur_pengawasan SET nama = ?, nomor = ?, status = ?, pembuat_id = ?, penyusun_id = ?, tanggal_pembuatan = ?, tanggal_revisi = ?, tanggal_efektif = ?, disahkan_oleh = ?, updated_at = NOW() WHERE id = ?",
            [
                $data["nama"],
                $data["nomor"],
                $data["status"],
                $data["pembuat_id"],
                $data["penyusun_id"],
                $data["tanggal_pembuatan"] ?? null,
                $data["tanggal_revisi"] ?? null,
                $data["tanggal_efektif"] ?? null,
                $data["disahkan_oleh"],
                $id,
            ],
        );
    }

    public static function findCover($id)
    {
        return DB::selectOne(
            "
            SELECT pp.id, pp.nomor, pp.nama, pp.tanggal_pembuatan, pp.tanggal_revisi, pp.tanggal_efektif, pp.cover, iu.nama AS disahkan_oleh_nama, iu.nip AS disahkan_oleh_nip, iu.jabatan AS disahkan_oleh_jabatan
            FROM prosedur_pengawasan pp
            INNER JOIN inspektur_utama iu ON pp.disahkan_oleh = iu.id
            WHERE pp.id = ?",
            [$id],
        );
    }

    public static function updateCover($id, $data)
    {
        return DB::update(
            "UPDATE prosedur_pengawasan SET cover = ?, updated_at = NOW() WHERE id = ?",
            [$data["cover"], $id],
        );
    }

    public static function findBody($id)
    {
        return DB::selectOne(
            "SELECT id, isi FROM prosedur_pengawasan WHERE id = ?",
            [$id],
        );
    }

    public static function updateBody($id, $data)
    {
        return DB::update(
            "UPDATE prosedur_pengawasan SET isi = ?, updated_at = NOW() WHERE id = ?",
            [$data["isi"], $id],
        );
    }

    public static function show($id)
    {
        return DB::selectOne(
            "
            SELECT pp.*, u1.name AS petugas_nama, u2.name AS perencana_nama,
                   iu.nama AS disahkan_oleh_nama, iu.nip AS disahkan_oleh_nip, iu.jabatan AS disahkan_oleh_jabatan
            FROM prosedur_pengawasan pp
            INNER JOIN users u1 ON pp.penyusun_id = u1.id
            INNER JOIN users u2 ON pp.pembuat_id = u2.id
            INNER JOIN inspektur_utama iu ON pp.disahkan_oleh = iu.id
            WHERE pp.id = ?",
            [$id],
        );
    }

    public static function delete($id)
    {
        return DB::delete("DELETE FROM prosedur_pengawasan WHERE id = ?", [
            $id,
        ]);
    }
}
