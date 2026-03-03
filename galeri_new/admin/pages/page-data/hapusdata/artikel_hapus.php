<?php
include "koneksi.php";

// Pastikan parameter ID tersedia dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data artikel untuk menghapus gambar dari folder juga
    $cek = mysqli_query($k, "SELECT gambar FROM artikel WHERE id = $id");
    if ($data = mysqli_fetch_assoc($cek)) {
        $gambar = $data['gambar'];

        // Hapus file gambar dari folder jika ada
        $gambarPath = "../img/" . $gambar;
        if (file_exists($gambarPath) && is_file($gambarPath)) {
            unlink($gambarPath);
        }

        // Hapus data dari database
        $hapus = mysqli_query($k, "DELETE FROM artikel WHERE id = $id");

        if ($hapus) {
            echo "<script>alert('Artikel berhasil dihapus');location='.?hal=artikel';</script>";
        } else {
            echo "<script>alert('Gagal menghapus artikel');history.back();</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan');history.back();</script>";
    }
} else {
    echo "<script>alert('ID tidak valid');history.back();</script>";
}
?>
