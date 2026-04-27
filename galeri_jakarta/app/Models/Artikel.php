<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika di database namanya 'artikel' (bukan 'artikels')
    protected $table = 'artikel'; 

    // Mengizinkan semua kolom diisi (Mass Assignment)
    protected $guarded = []; 
}