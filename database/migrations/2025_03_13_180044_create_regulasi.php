<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("regulasi", function (Blueprint $table) {
            $table->id();
            $table->string("judul");
            $table->string("tautan")->nullable();
            $table->string("file")->nullable();
            $table->timestamps();
            $table->unsignedBigInteger("perencana_id");

            $table
                ->foreign("perencana_id")
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
