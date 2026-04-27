<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    use HasFactory;

    protected $table = 'galeri_foto';
    protected $guarded = [];

    // Relasi balik ke galeri_folder
    public function folder()
    {
        return $this->belongsTo(GaleriFolder::class, 'folder_id', 'id');
    }
}