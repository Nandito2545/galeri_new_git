<?php include "koneksi.php"; ?>
<h3 class="mt-4">Tambah Galeri</h3>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama Galeri</label>
    <input type="text" name="nama" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required></textarea>
  </div>
  <div class="mb-3">
    <label>Gambar Folder Galeri</label>
    <input type="file" name="gambar" class="form-control" accept="image/*" required>
  </div>
  <button name="simpan" class="btn btn-dark">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
  $nama = mysqli_real_escape_string($k, $_POST['nama']);
  $desk = mysqli_real_escape_string($k, $_POST['deskripsi']);
  $gambar_folder = "";

  // Upload gambar
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar_folder = 'galeri_' . time() . '.' . $ext;
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../img/" . $gambar_folder);
  }

  // Simpan ke database
  mysqli_query($k, "INSERT INTO galeri_foto (nama_galeri, deskripsi, gambar_folder) VALUES ('$nama','$desk','$gambar_folder')");
  echo "<script>alert('Galeri berhasil ditambahkan');location='?hal=galeri';</script>";
}
?>
