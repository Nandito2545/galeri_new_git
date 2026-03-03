<?php
include "koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = mysqli_query($k, "SELECT * FROM artikel WHERE id = $id");
$artikel = mysqli_fetch_assoc($data);

if (!$artikel) {
  echo "<h4>Artikel tidak ditemukan.</h4>";
  exit;
}
?>

<div class="container py-4">
  <div class="card shadow-sm p-4" style="border-radius: 12px;">
    <h3 class="mb-2 fw-bold"><?= htmlspecialchars($artikel['judul']) ?></h3>

    <p class="text-muted mb-1">
      <?= date('d M Y', strtotime($artikel['tanggal'])) ?> - <?= htmlspecialchars($artikel['penulis']) ?>
    </p>

    <hr>

    <?php if ($artikel['gambar']) : ?>
      <img src="../img/<?= $artikel['gambar'] ?>" alt="gambar" class="img-fluid rounded mb-3" style="max-height: 400px; object-fit: cover;">
      <?php if (!empty($artikel['deskripsi_gambar'])): ?>
        <p class="text-muted"><em><?= htmlspecialchars($artikel['deskripsi_gambar']) ?></em></p>
      <?php endif; ?>
    <?php endif; ?>

    <div class="isi-artikel mt-3" style="line-height: 1.8;">
      <?= $artikel['isi'] ?>
    </div>

    <div class="mt-4">
      <a href=".?hal=artikel" class="btn btn-outline-dark">← Kembali ke daftar</a>
    </div>
  </div>
</div>
