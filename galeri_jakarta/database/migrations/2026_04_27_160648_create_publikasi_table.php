<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('kategori')->default('Umum');
            $table->string('thumbnail')->nullable();
            $table->string('file_pdf')->nullable(); // File PDF publikasi
            $table->text('ringkasan')->nullable();
            $table->longText('isi');
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamp('tanggal_publish')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasi');
    }
};
