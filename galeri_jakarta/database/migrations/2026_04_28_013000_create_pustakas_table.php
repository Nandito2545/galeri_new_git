<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pustakas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('kategori'); // Pasar Buku, Galeri Buku, Terlaris, Akan Terbit, Koleksi
            $table->bigInteger('harga')->default(0);
            $table->string('penerbit')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('halaman')->nullable();
            $table->string('bahasa')->default('Indonesia');
            $table->decimal('panjang', 5, 2)->nullable(); // cm
            $table->decimal('lebar', 5, 2)->nullable();   // cm
            $table->decimal('berat', 8, 2)->nullable();   // gram
            $table->longText('deskripsi')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pustakas');
    }
};
