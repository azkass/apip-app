<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("instrumen_pengawasan", function (Blueprint $table) {
            $table->id();
            $table->string("judul");
            $table->text("deskripsi")->nullable();
            $table->string("file");
            $table
                ->enum("status", ["draft", "diajukan", "disetujui"])
                ->default("draft");
            $table->unsignedBigInteger("pembuat_id");
            $table->unsignedBigInteger("pengelola_id");
            $table->timestamps();

            $table
                ->foreign("pengelola_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table
                ->foreign("pembuat_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("instrumen_pengawasan");
    }
};
