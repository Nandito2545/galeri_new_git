<?php
include "koneksi.php";

// Tangkap input pencarian
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($k, $_GET['cari']) : '';

// Query berdasarkan pencarian
if ($cari != '') {
    $pesan = mysqli_query($k, "SELECT * FROM pesan 
        WHERE nama LIKE '%$cari%' 
        OR email LIKE '%$cari%' 
        OR isi_pesan LIKE '%$cari%' 
        ORDER BY status_baca = 'sudah', tanggal_kirim DESC");
} else {
    $pesan = mysqli_query($k, "SELECT * FROM pesan 
        ORDER BY status_baca = 'sudah', tanggal_kirim DESC");
}
?>

<div class="container-fluid py-4">
  <h3 class="fw-bold mb-4">Semua Pesan Masuk</h3>

  <!-- Tampilkan hasil pencarian jika ada -->
  <?php if ($cari != ''): ?>
    <p class="text-muted">Hasil pencarian untuk: <strong><?= htmlspecialchars($cari) ?></strong></p>
  <?php endif; ?>

  <!-- Form pencarian -->
<form method="get" class="mb-3">
  <input type="hidden" name="hal" value="pesan">
  <div class="row g-2 align-items-center">
    <div class="col-md-5">
      <input type="text" name="cari" class="form-control" placeholder="Cari nama, email, atau isi pesan..." value="<?= htmlspecialchars($cari) ?>">
    </div>
    <div class="col-auto">
      <button class="btn btn-dark mt-3" type="submit">Cari</button>
      <?php if ($cari != ''): ?>
        <a href=".?hal=pesan" class="btn btn-secondary">Reset</a>
      <?php endif; ?>
    </div>
  </div>
</form>


  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Tanggal</th>
          <th>Isi Pesan</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        if (mysqli_num_rows($pesan) > 0):
          while($p = mysqli_fetch_assoc($pesan)):
        ?>
        <tr class="<?= $p['status_baca'] == 'belum' ? 'table-warning' : '' ?>">
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($p['nama']) ?></td>
          <td><?= htmlspecialchars($p['email']) ?></td>
          <td><?= date('d M Y H:i', strtotime($p['tanggal_kirim'])) ?></td>
          <td><?= substr(htmlspecialchars($p['isi_pesan']), 0, 60) ?>...</td>
          <td>
            <?php if ($p['status_baca'] == 'belum'): ?>
              <span class="badge bg-danger">Belum Dibaca</span>
            <?php else: ?>
              <span class="badge bg-success">Sudah Dibaca</span>
            <?php endif; ?>
          </td>
          <td>
            <a href=".?hal=pesan_detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-dark">Baca</a>

            <?php if ($p['status_baca'] == 'sudah'): ?>
              <a href="pesan_hapus.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')">Hapus</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="7" class="text-center">Tidak ada pesan ditemukan.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
