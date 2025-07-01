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
                "nama" => "Dr. Ahmad Susanto, S.H., M.H.",
                "nip" => "196501011990031001",
                "jabatan" => "Inspektur Utama Kementerian Keuangan",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama" => "Dra. Siti Nurhaliza, M.Si.",
                "nip" => "197203152000032002",
                "jabatan" => "Inspektur Utama Kementerian Dalam Negeri",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama" => "Prof. Dr. Bambang Wijaya, S.E., M.M.",
                "nip" => "196812071995031003",
                "jabatan" =>
                    "Inspektur Utama Kementerian Pendidikan dan Kebudayaan",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama" => "Ir. Indira Sari, M.T.",
                "nip" => "197505101998032004",
                "jabatan" => "Inspektur Utama Kementerian Pekerjaan Umum",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama" => "Dr. Eko Prasetyo, S.H., M.H.",
                "nip" => "196909151992031005",
                "jabatan" => "Inspektur Utama Kementerian Hukum dan HAM",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        DB::table("inspektur_utama")->insert($inspekturUtama);
    }
}
