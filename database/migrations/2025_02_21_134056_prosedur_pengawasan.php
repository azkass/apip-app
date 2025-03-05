<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("prosedur_pengawasan", function (Blueprint $table) {
            $table->id(); // Kolom `id` sebagai primary key (BIGINT, AUTO_INCREMENT)
            $table->string("name"); // Kolom `name` untuk menyimpan nama prosedur (VARCHAR)
            $table->text("description")->nullable(); // Kolom `description` untuk deskripsi (TEXT, opsional)
            $table->json("data"); // Kolom `data` untuk menyimpan struktur flowchart dalam format JSON
            $table->timestamps(); // Kolom `created_at` dan `updated_at` untuk timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("prosedur_pengawasan"); // Hapus tabel jika migration di-rollback
    }
};
