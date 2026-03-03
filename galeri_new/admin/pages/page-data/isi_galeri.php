<?php
include "koneksi.php";
$id = $_GET['id'];
$galeri = mysqli_fetch_assoc(mysqli_query($k, "SELECT * FROM galeri_foto WHERE id=$id"));
?>

<h3>Isi Galeri: <?= $galeri['nama_galeri'] ?></h3>
<a href="?hal=isi_galeri_tambah&id=<?= $id ?>" class="btn btn-dark mb-3">+ Tambah Foto</a>
<table class="table table-bordered">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Foto</th>
      <th>Deskripsi</th>
      <th>Tanggal</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    $isi = mysqli_query($k, "SELECT * FROM isi_galeri WHERE galeri_id=$id ORDER BY tanggal_upload DESC");
    while ($g = mysqli_fetch_assoc($isi)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><img src="../img/<?= $g['nama_file'] ?>" width="100"></td>
      <td><?= $g['deskripsi_foto'] ?></td>
      <td><?= $g['tanggal_upload'] ?></td>
      <td>
        <a href="?hal=isi_galeri_edit&id=<?= $g['id'] ?>&gid=<?= $id ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="?hal=isi_galeri_hapus&id=<?= $g['id'] ?>&gid=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
    </td>

    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
