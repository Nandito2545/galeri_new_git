<?php
include "koneksi.php";

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;

// Update status jadi 'sudah'
mysqli_query($k, "UPDATE pesan SET status_baca='sudah' WHERE id=$id");

// Ambil data pesan
$p = mysqli_fetch_assoc(mysqli_query($k, "SELECT * FROM pesan WHERE id=$id"));
?>

<div class="container py-4">
  <h3 class="fw-bold mb-4">Detail Pesan Dari <?= htmlspecialchars($p['nama']) ?></h3>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-6 mb-2">
          <strong>Nama:</strong><br>
          <?= htmlspecialchars($p['nama']) ?>
        </div>
        <div class="col-md-6 mb-2">
          <strong>Organisasi:</strong><br>
          <?= htmlspecialchars($p['organisasi']) ?>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6 mb-2">
          <strong>Email:</strong><br>
          <?= htmlspecialchars($p['email']) ?>
        </div>
        <div class="col-md-6 mb-2">
          <strong>No. Telepon:</strong><br>
          <?= htmlspecialchars($p['no_telepon']) ?>
        </div>
      </div>

      <div class="mb-3">
        <strong>Alamat Lengkap:</strong><br>
        <?= nl2br(htmlspecialchars($p['alamat_lengkap'])) ?>
      </div>

      <div class="mb-3">
        <strong>Waktu Pengiriman:</strong><br>
        <?= date('d M Y H:i', strtotime($p['tanggal_kirim'])) ?>
      </div>

      <hr>

      <div class="mb-3">
        <strong>Isi Pesan:</strong><br>
        <div class="bg-light border rounded p-3">
          <?= nl2br(htmlspecialchars($p['isi_pesan'])) ?>
        </div>
      </div>

      <a href="?hal=pesan" class="btn btn-outline-secondary mt-3">← Kembali ke Daftar Pesan</a>
    </div>
  </div>
</div>
