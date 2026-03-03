<?php
include "koneksi.php";

/* ======================
   AMBIL DATA
====================== */
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query(
  $k,"SELECT * FROM publications WHERE id='$id'"
));
?>

<h3 class="mt-4">Edit Publication</h3>
<hr>

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $data['id'] ?>">

<!-- Kategori -->
<div class="mb-3">
  <label class="form-label">Kategori</label>
  <select name="category" class="form-control" required>
    <option <?= $data['category']=='Education'?'selected':'' ?>>Education</option>
    <option <?= $data['category']=='Research'?'selected':'' ?>>Research</option>
    <option <?= $data['category']=='Report'?'selected':'' ?>>Report</option>
  </select>
</div>

<!-- Judul -->
<div class="mb-3">
  <label class="form-label">Judul</label>
  <input type="text" name="title" class="form-control"
         value="<?= htmlspecialchars($data['title']) ?>" required>
</div>

<!-- Deskripsi -->
<div class="mb-3">
  <label class="form-label">Deskripsi</label>
  <textarea name="description" class="form-control" rows="4" required>
<?= htmlspecialchars($data['description']) ?>
</textarea>
</div>

<!-- IMAGE -->
<div class="mb-3">
  <label class="form-label">Gambar Cover</label><br>
  <img src="../uploads/publications/images/<?= $data['image'] ?>"
       width="150" class="mb-2 rounded"><br>
  <input type="file" name="image" class="form-control">
  <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
</div>

<!-- PDF -->
<div class="mb-3">
  <label class="form-label">File PDF</label><br>
  <a href="../uploads/publications/pdf/<?= $data['file'] ?>"
     target="_blank" class="btn btn-sm btn-success mb-2">
     Lihat PDF
  </a>
  <input type="file" name="file" class="form-control mt-2" accept=".pdf">
  <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
</div>

<!-- STATUS -->
<div class="mb-4">
  <label class="form-label">Status</label>
  <select name="status" class="form-control" required>
    <option value="draft" <?= $data['status']=='draft'?'selected':'' ?>>Draft</option>
    <option value="publish" <?= $data['status']=='publish'?'selected':'' ?>>Publish</option>
  </select>
</div>

<button type="submit" name="update" class="btn btn-dark">
  Update Publication
</button>

</form>

<?php
/* ======================
   PROSES UPDATE
====================== */
if(isset($_POST['update'])){

  $id          = $_POST['id'];
  $category    = mysqli_real_escape_string($k,$_POST['category']);
  $title       = mysqli_real_escape_string($k,$_POST['title']);
  $description = mysqli_real_escape_string($k,$_POST['description']);
  $status      = mysqli_real_escape_string($k,$_POST['status']);

  $img_dir = "../uploads/publications/images/";
  $pdf_dir = "../uploads/publications/pdf/";

  // ambil file lama
  $old = mysqli_fetch_assoc(mysqli_query(
    $k,"SELECT image,file FROM publications WHERE id='$id'"
  ));

  $img_name  = $old['image'];
  $file_name = $old['file'];

  /* ======================
     IMAGE BARU
  ====================== */
  if($_FILES['image']['error'] === 0){

    if(file_exists($img_dir.$img_name)){
      unlink($img_dir.$img_name);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $img_name = 'pub_img_'.uniqid().'.'.$ext;
    move_uploaded_file($_FILES['image']['tmp_name'], $img_dir.$img_name);
  }

  /* ======================
     PDF BARU
  ====================== */
  if($_FILES['file']['error'] === 0){

    if(file_exists($pdf_dir.$file_name)){
      unlink($pdf_dir.$file_name);
    }

    $file_name = 'pub_file_'.uniqid().'.pdf';
    move_uploaded_file($_FILES['file']['tmp_name'], $pdf_dir.$file_name);
  }

  /* ======================
     UPDATE DB
  ====================== */
  $update = mysqli_query($k,"UPDATE publications SET
    category='$category',
    title='$title',
    description='$description',
    image='$img_name',
    file='$file_name',
    status='$status'
    WHERE id='$id'
  ");

  if($update){
    echo "<script>alert('Data berhasil diperbarui');
    location='?hal=publications';</script>";
  }else{
    echo mysqli_error($k);
  }
}
?>
