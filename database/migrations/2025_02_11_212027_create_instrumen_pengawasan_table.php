<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumen_pengawasan', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Judul instrumen
            $table->unsignedBigInteger('petugas_pengelola_id'); // ID petugas pengelola (role pjk/operator)
            $table->text('isi')->nullable(); // Isi instrumen (diisi oleh pjk/operator)
            $table->enum('status', ['draft', 'diajukan', 'disetujui'])->default('draft'); // Status instrumen
            $table->unsignedBigInteger('perencana_id'); // ID perencana yang membuat instrumen
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('petugas_pengelola_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('perencana_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumen_pengawasan');
    }
};
