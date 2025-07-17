<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class PertanyaanEvaluasi
{
    /**
     * Get all pertanyaan evaluasi records
     *
     * @return array
     */
    public static function index()
    {
        // Using a prepared statement with single query
        return DB::select(
            "SELECT id, pertanyaan, created_at FROM pertanyaan_evaluasi",
        );
    }

    /**
     * Find a pertanyaan by its ID
     *
     * @param int $id
     * @return object|null
     */
    public static function find($id)
    {
        // Using first() for efficiency when we only need one record
        $result = DB::selectOne(
            "SELECT * FROM pertanyaan_evaluasi WHERE id = ?",
            [$id],
        );
        return $result ?: null;
    }

    /**
     * Create a new pertanyaan record
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $now = now()->format("Y-m-d H:i:s");
        // Using a single insert operation
        return DB::insert(
            "INSERT INTO pertanyaan_evaluasi (pertanyaan, created_at, updated_at) VALUES (?, ?, ?)",
            [trim($data["pertanyaan"]), $now, $now],
        );
    }

    /**
     * Update an existing pertanyaan record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        // Using a single update operation with data sanitization
        return DB::update(
            "UPDATE pertanyaan_evaluasi SET pertanyaan = ?, updated_at = ? WHERE id = ?",
            [trim($data["pertanyaan"]), now()->format("Y-m-d H:i:s"), $id],
        );
    }

    /**
     * Delete a pertanyaan record
     *
     * @param int $id
     * @return bool
     */
    public static function destroy($id)
    {
        // Simple, direct deletion
        return DB::delete("DELETE FROM pertanyaan_evaluasi WHERE id = ?", [
            $id,
        ]);
    }
}
