<?php
include "koneksi.php";

$id = (int) $_GET['id'];

// Cek apakah data ada
$cek = mysqli_query($k, "SELECT * FROM pesan WHERE id = $id");

if ($data = mysqli_fetch_assoc($cek)) {
    if ($data['status_baca'] === 'sudah') {
        // Hapus pesan
        mysqli_query($k, "DELETE FROM pesan WHERE id = $id");
        echo "<script>alert('Pesan berhasil dihapus'); window.location.href='.?hal=pesan';</script>";
    } else {
        // Tidak bisa dihapus
        echo "<script>alert('Pesan belum dibaca, tidak dapat dihapus!'); window.location.href='.?hal=pesan';</script>";
    }
} else {
    echo "<script>alert('Pesan tidak ditemukan'); window.location.href='.?hal=pesan';</script>";
}
?>
