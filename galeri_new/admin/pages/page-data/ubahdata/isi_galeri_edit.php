<?php
include "koneksi.php";

// Ambil ID foto yang mau diedit
$id_foto = intval($_GET['id']);

// Ambil data foto
$data = mysqli_fetch_assoc(mysqli_query($k, "SELECT * FROM isi_galeri WHERE id = $id_foto"));
$galeri_id = $data['galeri_id'];
?>

<h3>Edit Foto Galeri</h3>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Gambar Sekarang</label><br>
    <img src="../img/<?= $data['nama_file'] ?>" width="200" class="mb-2"><br>
    <input type="file" name="gambar" class="form-control">
    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
  </div>
  <div class="mb-3">
    <label>Deskripsi Foto</label>
    <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi_foto']) ?></textarea>
  </div>
  <button name="update" class="btn btn-success">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
  $desc = mysqli_real_escape_string($k, $_POST['deskripsi']);
  $nama_lama = $data['nama_file'];
  $nama_baru = $nama_lama;

  // Jika gambar baru di-upload
  if (!empty($_FILES['gambar']['name'])) {
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $nama_baru = 'foto_' . time() . '.' . $ext;

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "../img/$nama_baru")) {
      // Hapus gambar lama
      if (file_exists("../img/$nama_lama")) {
        unlink("../img/$nama_lama");
      }
    } else {
      echo "<div class='alert alert-danger'>Gagal mengupload gambar baru.</div>";
      exit;
    }
  }

  mysqli_query($k, "UPDATE isi_galeri SET nama_file='$nama_baru', deskripsi_foto='$desc' WHERE id=$id_foto");
  echo "<script>alert('Foto berhasil diperbarui');location='?hal=isi_galeri&id=$galeri_id';</script>";
}
?>
