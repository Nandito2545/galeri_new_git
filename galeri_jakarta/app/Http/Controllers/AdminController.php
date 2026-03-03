<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Hitung pesan belum dibaca
        $notif_pesan = DB::table('pesan')->where('status_baca', 'belum')->count();

        // Ambil data untuk ditampilkan
        $artikel = DB::table('artikel')->orderBy('tanggal_publish', 'desc')->limit(3)->get();
        $pesan = DB::table('pesan')->orderBy('tanggal_kirim', 'desc')->limit(5)->get();
        $galeri = DB::table('galeri_folder')->orderBy('tanggal_dibuat', 'desc')->limit(5)->get();

        // Kirim data ke view admin.beranda
        return view('admin.beranda', compact('notif_pesan', 'artikel', 'pesan', 'galeri'));
    }
}