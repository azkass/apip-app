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
        Schema::create("monev_prosedur_pengawasan", function (
            Blueprint $table,
        ) {
            $table->id();
            $table->unsignedBigInteger("sop_id");
            $table->string("penilaian_penerapan");
            $table->text("catatan_penilaian");
            $table->string("tindakan");
            $table->enum("mampu_mendorong_kinerja", ["ya", "tidak"]);
            $table->enum("mampu_dipahami", ["ya", "tidak"]);
            $table->enum("mudah_dilaksanakan", ["ya", "tidak"]);
            $table->enum("dapat_menjalankan_peran", ["ya", "tidak"]);
            $table->enum("mampu_mengatasi_permasalahan", ["ya", "tidak"]);
            $table->enum("mampu_menjawab_kebutuhan", ["ya", "tidak"]);
            $table->enum("sinergi_dengan_lainnya", ["ya", "tidak"]);
            $table->timestamps();

            $table
                ->foreign("sop_id")
                ->references("id")
                ->on("prosedur_pengawasan")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("monev_prosedur_pengawasan");
    }
};
