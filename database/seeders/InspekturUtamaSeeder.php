<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspekturUtamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inspekturUtama = [
            [
                "id" => 1,
                "nama" => "Dr. Dadang Hardiwan, S.Si., M.Si ",
                "nip" => "197206091994121001",
                "jabatan" => "Inspektur Utama",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        DB::table("inspektur_utama")->insert($inspekturUtama);
    }
}
