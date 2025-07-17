<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PertanyaanEvaluasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pertanyaan = [
            [
                "id" => 1,
                "pertanyaan" => "Mampu mendorong peningkatan kinerja",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 2,
                "pertanyaan" => "Mudah dipahami",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 3,
                "pertanyaan" => "Mudah dilaksanakan",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 4,
                "pertanyaan" =>
                    "Semua orang dapat menjalankan perannya masing-masing",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 5,
                "pertanyaan" =>
                    "Mampu mengatasi permasalahan yang berkaitan dengan proses",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 6,
                "pertanyaan" =>
                    "Mampu menjawab kebutuhan peningkatan kinerja organisasi",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 7,
                "pertanyaan" => "Sinergi satu dengan lainnya",
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ];

        DB::table("pertanyaan_evaluasi")->insert($pertanyaan);
    }
}
