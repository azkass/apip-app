<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("prosedur_pengawasan", function (Blueprint $table) {
            $table->id();
            $table->string("nomor");
            $table->string("judul");
            $table->date("tanggal_pembuatan")->nullable();
            $table->date("tanggal_revisi")->nullable();
            $table->date("tanggal_efektif")->nullable();
            $table->unsignedBigInteger("disahkan_oleh");
            $table->json("cover")->nullable();
            $table->json("isi")->nullable();
            $table
                ->enum("status", ["draft", "diajukan", "disetujui"])
                ->default("draft");
            $table->unsignedBigInteger("pembuat_id");
            $table->unsignedBigInteger("penyusun_id");
            $table->timestamps();

            $table
                ->foreign("disahkan_oleh")
                ->references("id")
                ->on("inspektur_utama")
                ->onDelete("cascade");

            $table
                ->foreign("pembuat_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table
                ->foreign("penyusun_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("prosedur_pengawasan");
    }
};
