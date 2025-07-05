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
        Schema::create("evaluasi_prosedur", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sop_id");
            $table->unsignedBigInteger("pertanyaan_id");
            $table->boolean("jawaban");
            $table->timestamps();

            $table
                ->foreign("sop_id")
                ->references("id")
                ->on("prosedur_pengawasan")
                ->onDelete("cascade");

            $table
                ->foreign("pertanyaan_id")
                ->references("id")
                ->on("pertanyaan_evaluasi")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("evaluasi_prosedur");
    }
};
