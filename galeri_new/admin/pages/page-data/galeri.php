<?php include "koneksi.php"; ?>

<?php
$search = $_GET['cari'] ?? ''; // ambil nilai search jika ada

// Query dengan pencarian
$where = '';
if ($search !== '') {
    $safe = mysqli_real_escape_string($k, $search);
    $where = "WHERE nama_galeri LIKE '%$safe%'";
}

$query = "SELECT * FROM galeri_foto $where ORDER BY tanggal_dibuat DESC";
$galeri = mysqli_query($k, $query);

// Jika ada submit hapus banyak
if(isset($_POST['hapus_banyak'])) {
    $ids = $_POST['galeri_id'] ?? [];
    if($ids) {
        $ids_str = implode(',', array_map('intval', $ids));
        mysqli_query($k, "DELETE FROM galeri_foto WHERE id IN ($ids_str)");
        echo "<script>alert('Galeri berhasil dihapus!'); window.location='.?hal=galeri';</script>";
    }
}
?>

<style>
  .form-control{
    height: 38px !important;
  }
</style>

<h3 class="fw-bold mb-3">Kelola Galeri</h3>

<!-- Form Search -->
<form method="get" class="mb-3 row align-items-end">
  <input type="hidden" name="hal" value="galeri">

  <!-- Kolom Tambah Galeri (Kiri) -->
  <div class="col-md-6">
    <a href=".?hal=galeri_tambah" class="btn btn-dark">+ Tambah Galeri</a>
  </div>

  <!-- Kolom Pencarian (Kanan) -->
  <div class="col-md-6 d-flex justify-content-end gap-2">
    <input type="text" name="cari" class="form-control" placeholder="Cari nama galeri..." value="<?= htmlspecialchars($search) ?>" style="max-width: 250px; box-shadow: 0 3px 9px rgba(0, 0, 0, 0.2);">
    <button type="submit" class="btn btn-danger">Cari</button>
    <a href=".?hal=galeri" class="btn btn-secondary">Reset</a>
  </div>
</form>

<!-- Tabel Galeri dengan Checkbox -->
<form method="post" onsubmit="return confirm('Yakin ingin menghapus galeri terpilih?')">
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th><input type="checkbox" id="checkAll"></th>
        <th>No</th>
        <th>Nama Galeri</th>
        <th>Gambar Folder</th>
        <th>Deskripsi</th>
        <th>Tanggal Dibuat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while($g = mysqli_fetch_assoc($galeri)): ?>
      <tr>
        <td><input type="checkbox" name="galeri_id[]" value="<?= $g['id'] ?>"></td>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($g['nama_galeri']) ?></td>
        <td><img src="../img/<?= $g['gambar_folder'] ?>" width="100" alt="gambar" style="object-fit:cover; max-height:100px;"></td>
        <td><?= htmlspecialchars($g['deskripsi']) ?></td>
        <td><?= date('d M Y', strtotime($g['tanggal_dibuat'])) ?></td>
        <td>
          <a href=".?hal=isi_galeri&id=<?= $g['id'] ?>" class="btn btn-sm btn-info">Lihat Isi</a>
          <a href=".?hal=galeri_edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href=".?hal=galeri_hapus&id=<?= $g['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus galeri ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <button type="submit" name="hapus_banyak" class="btn btn-danger">Hapus Terpilih</button>
</form>

<script>
  // Checkbox select all
  document.getElementById('checkAll').addEventListener('click', function() {
    const checkboxes = document.querySelectorAll('input[name="galeri_id[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>
