<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("regulasi", function (Blueprint $table) {
            $table->id();
            $table->string("tahun");
            $table->string("nomor");
            $table->string("tentang");
            $table->enum("jenis_peraturan", [
                "peraturan_bps",
                "peraturan_kepala_bps",
                "surat_edaran_kepala_bps",
                "keputusan_kepala_bps",
                "surat_edaran_irtama_bps",
                "keputusan_irtama_bps",
            ]);
            $table->enum("status", ["berlaku", "tidak_berlaku"]);
            $table->string("tautan")->nullable();
            $table->string("file")->nullable();
            $table->unsignedBigInteger("pembuat_id");
            $table->timestamps();

            $table
                ->foreign("pembuat_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("regulasi");
    }
};
