<?php
include "koneksi.php";

$id = (int)$_GET['id'];
$gid = (int)$_GET['gid'];

// Ambil nama file gambar
$result = mysqli_query($k, "SELECT nama_file FROM isi_galeri WHERE id = $id LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
  $foto = mysqli_fetch_assoc($result);
  $filePath = "../img/" . $foto['nama_file'];

  // Hapus file fisik jika ada
  if (file_exists($filePath)) {
    if (!unlink($filePath)) {
      echo "<script>alert('Gagal menghapus file fisik!'); location='?hal=isi_galeri&id=$gid';</script>";
      exit;
    }
  }
}

// Hapus data dari database
mysqli_query($k, "DELETE FROM isi_galeri WHERE id = $id");

echo "<script>alert('Foto berhasil dihapus');location='?hal=isi_galeri&id=$gid';</script>";
?>
