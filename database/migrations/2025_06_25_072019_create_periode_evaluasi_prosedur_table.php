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
        Schema::create("periode_evaluasi_prosedur", function (
            Blueprint $table
        ) {
            $table->id();
            $table->unsignedBigInteger("pembuat_id");
            $table->date("mulai");
            $table->date("berakhir");
            $table->timestamps();

            $table
                ->foreign("pembuat_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("periode_evaluasi_prosedur");
    }
};
