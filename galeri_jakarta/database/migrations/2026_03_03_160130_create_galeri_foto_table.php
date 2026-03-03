<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('galeri_folder', function (Blueprint $table) {
    $table->id();
    $table->string('nama_folder');
    $table->string('foto_cover'); // foto utama folder
    $table->text('deskripsi')->nullable();
    $table->timestamp('tanggal_dibuat')->useCurrent();
    $table->timestamps();
});
Schema::create('galeri_foto', function (Blueprint $table) {
    $table->id();
    $table->foreignId('folder_id')->constrained('galeri_folder')->onDelete('cascade');
    $table->string('nama_foto');
    $table->string('file_foto');
    $table->text('deskripsi')->nullable();
    $table->timestamp('tanggal_upload')->useCurrent();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_foto');
    }
};
