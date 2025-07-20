<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat direktori penyimpanan jika belum ada
        $this->createStorageDirectories();

        // Jalankan seeders
        $this->call([
            // PertanyaanEvaluasiSeeder::class,
            InspekturUtamaSeeder::class,
        ]);
    }

    /**
     * Membuat direktori penyimpanan yang diperlukan
     */
    protected function createStorageDirectories(): void
    {
        $directories = ["private/prosedur"];

        foreach ($directories as $directory) {
            $path = storage_path("app/" . $directory);

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->command->info("Created directory: {$path}");
            }
        }
    }
}
