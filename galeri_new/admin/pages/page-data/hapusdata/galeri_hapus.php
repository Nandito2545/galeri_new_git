<?php
include "koneksi.php";

$id = intval($_GET['id']);

// Hapus gambar terlebih dahulu
$data = mysqli_fetch_assoc(mysqli_query($k, "SELECT gambar_folder FROM galeri_foto WHERE id = $id"));
if ($data && $data['gambar_folder']) {
  $path = "../img/" . $data['gambar_folder'];
  if (file_exists($path)) {
    unlink($path);
  }
}

// Hapus data dari database (isi_galeri otomatis ikut terhapus karena ON DELETE CASCADE)
mysqli_query($k, "DELETE FROM galeri_foto WHERE id = $id");

echo "<script>alert('Galeri berhasil dihapus');location='?hal=galeri';</script>";
?>
