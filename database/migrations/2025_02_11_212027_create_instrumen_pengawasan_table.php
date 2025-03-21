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
            $table->unsignedBigInteger("petugas_pengelola_id");
            $table->text("isi")->nullable();
            $table
                ->enum("status", ["draft", "diajukan", "disetujui"])
                ->default("draft");
            $table->unsignedBigInteger("perencana_id");
            $table->timestamps();

            $table
                ->foreign("petugas_pengelola_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table
                ->foreign("perencana_id")
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
