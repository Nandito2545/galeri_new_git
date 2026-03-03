<?php
include "koneksi.php";

// Ambil data berdasarkan ID
$id = intval($_GET['id']);
$galeri = mysqli_fetch_assoc(mysqli_query($k, "SELECT * FROM galeri_foto WHERE id = $id"));
?>

<h3 class="mt-4">Edit Galeri</h3>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Nama Galeri</label>
    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($galeri['nama_galeri']) ?>" required>
  </div>
  <div class="mb-3">
    <label>Deskripsi</label>
    <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($galeri['deskripsi']) ?></textarea>
  </div>
  <div class="mb-3">
    <label>Gambar Folder Galeri (Biarkan kosong jika tidak diganti)</label><br>
    <img src="../img/<?= $galeri['gambar_folder'] ?>" width="150" class="mb-2"><br>
    <input type="file" name="gambar" class="form-control" accept="image/*">
  </div>
  <button name="update" class="btn btn-success">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
  $nama = mysqli_real_escape_string($k, $_POST['nama']);
  $desk = mysqli_real_escape_string($k, $_POST['deskripsi']);
  $gambar_folder = $galeri['gambar_folder'];

  // Jika ada file baru di-upload
  if (!empty($_FILES['gambar']['name'])) {
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar_baru = 'galeri_' . time() . '.' . $ext;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], "../img/" . $gambar_baru)) {
      if ($gambar_folder && file_exists("../img/" . $gambar_folder)) {
        unlink("../img/" . $gambar_folder); // hapus gambar lama
      }
      $gambar_folder = $gambar_baru;
    }
  }

  mysqli_query($k, "UPDATE galeri_foto SET 
      nama_galeri = '$nama',
      deskripsi = '$desk',
      gambar_folder = '$gambar_folder' 
    WHERE id = $id");

  echo "<script>alert('Galeri berhasil diupdate');location='?hal=galeri';</script>";
}
?>
