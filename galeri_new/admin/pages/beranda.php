<?php
include "koneksi.php";

// Hitung notifikasi pesan belum dibaca
$notif_pesan = mysqli_fetch_assoc(mysqli_query($k, "
  SELECT COUNT(*) AS total FROM pesan WHERE status_baca = 'belum'
"))['total'];

// Ambil data untuk ditampilkan
$artikel = mysqli_query($k, "SELECT * FROM artikel ORDER BY tanggal DESC LIMIT 3");
$pesan   = mysqli_query($k, "SELECT * FROM pesan ORDER BY tanggal_kirim DESC LIMIT 5");
$galeri  = mysqli_query($k, "SELECT * FROM galeri_foto ORDER BY tanggal_dibuat DESC LIMIT 5");
?>
<style>
  /* Hover umum untuk semua tombol */
  a.btn {
    transition: all 0.2s ease-in-out;
  }

  /* Efek hover untuk tombol btn-dark */
  a.btn-dark:hover {
    background-color:rgb(3, 104, 206) !important;
    color:rgb(255, 255, 255) !important; /* Kuning saat hover */
    transform: scale(1.03);
  }

  /* Efek hover untuk tombol btn-outline-dark */
  a.btn-outline-dark:hover {
    background-color: #212529 !important;
    color: #fff !important;
    transform: scale(1.03);
  }

  /* Efek hover untuk tombol btn-outline-secondary */
  a.btn-outline-secondary:hover {
    background-color: #6c757d !important;
    color: #fff !important;
    transform: scale(1.03);
  }

  /* Efek hover untuk tombol btn-secondary */
  a.btn-secondary:hover {
    background-color: #5a6268 !important;
    color: #fff !important;
    transform: scale(1.03);
  }

  /* Efek hover untuk tombol btn-danger */
  a.btn-danger:hover {
    background-color: #c82333 !important;
    color: #fff !important;
    transform: scale(1.03);
  }

  /* Tambahan smooth text link hover */
  a:hover {
    text-decoration: none;
    opacity: 0.9;
  }
</style>



<div class="container-fluid py-4">
  <h3 class="fw-bold mb-4">Dashboard Admin GBJ</h3>

  <!-- Aksi Cepat -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="c text-white p-3">
        <h5 class="mb-1">Tambah Artikel</h5>
        <p class="mb-3">Tulis dan publikasikan artikel baru.</p>
        <a href=".?hal=artikel_tambah" class="btn btn-dark btn-sm">Tambah</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="c text-white p-3 ">
        <h5 class="mb-1">Tambah Galeri</h5>
        <p class="mb-3">Buat folder galeri dan unggah gambar.</p>
        <a href=".?hal=galeri_tambah" class="btn btn-dark btn-sm">Tambah</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="c text-dark p-3">
        <h5 class="mb-1">
          Pesan Masuk
          <?php if ($notif_pesan > 0): ?>
            <span class="badge bg-danger"><?= $notif_pesan ?></span>
          <?php endif; ?>
        </h5>
        <p class="mb-3">Cek semua pesan dari pengguna.</p>
        <a href=".?hal=pesan" class="btn btn-dark btn-sm">
          Lihat
          <?php if ($notif_pesan > 0): ?>
            <span class="badge bg-warning text-dark ms-1"><?= $notif_pesan ?></span>
          <?php endif; ?>
        </a>
      </div>
    </div>

  </div>

  <!-- Artikel Terbaru -->
  <div class="bg-white p-4 mb-4 rounded shadow-sm border">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Artikel Terbaru</h5>
      <a href=".?hal=artikel" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
    </div>
    <?php while($a = mysqli_fetch_assoc($artikel)): ?>
      <div class="border-bottom py-2">
        <div class="d-flex justify-content-between">
          <div>
            <strong><?= htmlspecialchars($a['judul']) ?></strong><br>
            <small><?= date('d M Y', strtotime($a['tanggal'])) ?> - <?= htmlspecialchars($a['penulis']) ?></small>
          </div>
          <a href=".?hal=artikel_detail&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Pesan Terbaru + Notifikasi -->
<div class="bg-white p-4 mb-4 rounded shadow-sm border">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
      Pesan Terbaru
      <?php if ($notif_pesan > 0): ?>
        <span class="badge bg-danger ms-2"><?= $notif_pesan ?> belum dibaca</span>
      <?php endif; ?>
    </h5>
    <a href=".?hal=pesan" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
  </div>

  <?php while($p = mysqli_fetch_assoc($pesan)): ?>
    <div class="border-bottom py-2 <?= $p['status_baca'] == 'belum' ? 'bg-light border-start border-1' : '' ?>">
      <div class="d-flex justify-content-between">
        <div>
          <strong><?= htmlspecialchars($p['nama']) ?></strong>
          <?php if ($p['status_baca'] == 'belum'): ?>
            <span class="bg-warning text-dark ms-1">Belum dibaca</span>
          <?php endif; ?><br>
          <small><?= date('d M Y H:i', strtotime($p['tanggal_kirim'])) ?></small><br>
          <span><?= substr(htmlspecialchars($p['isi_pesan']), 0, 60) ?>...</span>
        </div>
        <a href=".?hal=pesan_detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Baca</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>


  <!-- Galeri Terbaru -->
  <div class="bg-white p-4 rounded shadow-sm border">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Galeri Foto Terbaru</h5>
      <a href=".?hal=galeri" class="btn btn-sm btn-outline-dark">Kelola Galeri</a>
    </div>
    <div class="row">
      <?php while($g = mysqli_fetch_assoc($galeri)): ?>
        <div class="col-md-4 mb-3">
          <div class="p-2 border rounded h-100">
            <h6 class="mb-1"><?= htmlspecialchars($g['nama_galeri']) ?></h6>
            <p class="mb-1"><?= nl2br(htmlspecialchars($g['deskripsi'])) ?></p>
            <small class="text-muted">Dibuat: <?= date('d M Y', strtotime($g['tanggal_dibuat'])) ?></small><br>
            <a href=".?hal=isi_galeri&id=<?= $g['id'] ?>" class="btn btn-sm btn-outline-secondary mt-2">Lihat</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>
