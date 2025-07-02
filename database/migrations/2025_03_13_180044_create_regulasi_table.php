<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("regulasi", function (Blueprint $table) {
            $table->id();
            $table->string("kode");
            $table->string("hasil_kerja");
            $table->string("judul");
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
