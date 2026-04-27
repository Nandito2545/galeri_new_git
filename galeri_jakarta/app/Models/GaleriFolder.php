<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFolder extends Model
{
    use HasFactory;

    protected $table = 'galeri_folder'; // Nama tabel di database
    protected $guarded = []; // Izinkan isi semua kolom

    // Relasi ke tabel galeri_foto (Satu folder punya banyak foto)
    public function fotos()
    {
        return $this->hasMany(GaleriFoto::class, 'folder_id', 'id');
    }
}