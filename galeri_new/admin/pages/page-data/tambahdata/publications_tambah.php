<?php
include "koneksi.php";
?>

<h3 class="mt-4">Form Tambah Publication</h3>
<hr>

<style>
body {
  overflow-x: hidden;
}
.container, .row, form {
  max-width: 100%;
  overflow-x: hidden;
}
</style>

<form method="post" action="" enctype="multipart/form-data">

<!-- Kategori -->
<div class="row mb-3">
  <div class="col-md-12">
    <label class="form-label">Kategori</label>
    <select name="category" class="form-control" required>
      <option value="Education">Education</option>
      <option value="Research">Research</option>
      <option value="Report">Report</option>
    </select>
  </div>
</div>

<!-- Judul -->
<div class="row mb-3">
  <div class="col-md-12">
    <div class="form-floating">
      <input name="title" class="form-control" type="text" placeholder="Masukkan Judul" required>
      <label>Judul Publication</label>
    </div>
  </div>
</div>

<!-- Deskripsi -->
<div class="row mb-3">
  <div class="col-md-12">
    <div class="form-floating">
      <textarea name="description" class="form-control" placeholder="Masukkan deskripsi"
        style="height:120px" required></textarea>
      <label>Deskripsi Singkat</label>
    </div>
  </div>
</div>

<!-- Gambar -->
<div class="row mb-3">
  <div class="col-md-12">
    <label class="form-label">Gambar Cover</label>
    <input type="file" name="image" class="form-control" required>
  </div>
</div>

<!-- File Download -->
<div class="row mb-3">
  <div class="col-md-12">
    <label class="form-label">File Publication (PDF)</label>
    <input type="file" name="file" class="form-control" accept=".pdf" required>
  </div>
</div>

<!-- Tanggal -->
<div class="row mb-3">
  <div class="col-md-12">
    <div class="form-floating">
      <input name="created_at" class="form-control" type="date" required>
      <label>Tanggal</label>
    </div>
  </div>
</div>

<!-- Status -->
<div class="row mb-4">
  <div class="col-md-12">
    <label class="form-label">Status</label>
    <select name="status" class="form-control" required>
      <option value="draft">Draft</option>
      <option value="publish">Publish</option>
    </select>
  </div>
</div>

<!-- Submit -->
<div class="d-grid">
  <input type="submit" name="simpan" value="Simpan Publication" class="btn btn-dark">
</div>

<?php
if(isset($_POST['simpan'])){

  $category    = mysqli_real_escape_string($k,$_POST['category']);
  $title       = mysqli_real_escape_string($k,$_POST['title']);
  $description = mysqli_real_escape_string($k,$_POST['description']);
  $status      = mysqli_real_escape_string($k,$_POST['status']);
  $created_at  = $_POST['created_at'];

  /* ======================
     PATH FOLDER (FILESYSTEM)
  ====================== */
  $img_dir = "../uploads/publications/images/";
  $pdf_dir = "../uploads/publications/pdf/";

  // pastikan folder ada
  if(!is_dir($img_dir)) mkdir($img_dir, 0777, true);
  if(!is_dir($pdf_dir)) mkdir($pdf_dir, 0777, true);

  /* ======================
     UPLOAD IMAGE
  ====================== */
  $img_name = '';
  if($_FILES['image']['error'] === 0){

    $img_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed_img = ['jpg','jpeg','png','webp'];

    if(!in_array($img_ext, $allowed_img)){
      die("Format gambar tidak valid");
    }

    $img_name = 'pub_img_' . uniqid() . '.' . $img_ext;

    if(!move_uploaded_file($_FILES['image']['tmp_name'], $img_dir . $img_name)){
      die("Gagal upload gambar");
    }
  }

  /* ======================
     UPLOAD PDF
  ====================== */
  $file_name = '';
  if($_FILES['file']['error'] === 0){

    $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    if($file_ext !== 'pdf'){
      die("File publication harus PDF");
    }

    $file_name = 'pub_file_' . uniqid() . '.pdf';

    if(!move_uploaded_file($_FILES['file']['tmp_name'], $pdf_dir . $file_name)){
      die("Gagal upload PDF");
    }
  }

  /* ======================
     SIMPAN DATABASE
     (PATH RELATIF WEB)
  ====================== */
  $img_db = "$img_name";
  $pdf_db = "$file_name";

  $query = mysqli_query($k,"INSERT INTO publications
    (category,title,description,image,file,status,created_at)
    VALUES
    (
      '$category',
      '$title',
      '$description',
      '$img_db',
      '$pdf_db',
      '$status',
      '$created_at'
    )
  ");

  if($query){
    echo "<script>alert('Publication berhasil ditambahkan');
    location='?hal=publications';</script>";
  }else{
    echo "Gagal menyimpan data : ".mysqli_error($k);
  }
}
?>



</form>
