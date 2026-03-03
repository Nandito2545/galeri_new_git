<?php
include "koneksi.php";

/* =====================
   PARAMETER
===================== */
$category = $_GET['category'] ?? 'all';
$search   = $_GET['search'] ?? '';
$limit    = $_GET['limit'] ?? 10;
$page     = $_GET['page'] ?? 1;

$limit  = in_array($limit,[10,25,50,100]) ? (int)$limit : 10;
$page   = max(1,(int)$page);
$offset = ($page-1)*$limit;

/* =====================
   FILTER
===================== */
$where = [];

if($category!='all'){
  $where[] = "category='".mysqli_real_escape_string($k,$category)."'";
}

if($search){
  $where[] = "title LIKE '%".mysqli_real_escape_string($k,$search)."%'";
}

$whereSQL = $where ? "WHERE ".implode(" AND ",$where) : "";

/* =====================
   TOTAL DATA
===================== */
$qTotal = mysqli_query($k,"SELECT COUNT(*) total FROM publications $whereSQL");
$totalData = mysqli_fetch_assoc($qTotal)['total'];
$totalPage = ceil($totalData/$limit);

/* =====================
   DATA
===================== */
$data = mysqli_query($k,"
  SELECT * FROM publications
  $whereSQL
  ORDER BY created_at DESC
  LIMIT $limit OFFSET $offset
");

/* =====================
   BULK ACTION
===================== */
if(isset($_POST['bulk_action'],$_POST['pub_id'])){
  $ids = implode(',',array_map('intval',$_POST['pub_id']));

  if($_POST['bulk_action'] == 'delete' && !empty($_POST['pub_id'])){

  $ids = implode(',', array_map('intval', $_POST['pub_id']));

  // Ambil file image & pdf
  $qFile = mysqli_query($k,"
    SELECT image, file 
    FROM publications 
    WHERE id IN ($ids)
  ");

  while($f = mysqli_fetch_assoc($qFile)){

    // HAPUS IMAGE
    if(!empty($f['image'])){
      $imgPath = "../uploads/publications/images/".$f['image'];
      if(file_exists($imgPath)){
        unlink($imgPath);
      }
    }

    // HAPUS PDF
    if(!empty($f['file'])){
      $pdfPath = "../uploads/publications/pdf/".$f['file'];
      if(file_exists($pdfPath)){
        unlink($pdfPath);
      }
    }
  }

  // HAPUS DATA DB
  mysqli_query($k,"DELETE FROM publications WHERE id IN ($ids)");

  echo "<script>alert('Publication berhasil dihapus');location='?hal=publications';</script>";
}


  if($_POST['bulk_action']=='publish'){
    mysqli_query($k,"UPDATE publications SET status='publish' WHERE id IN ($ids)");
  }
  if($_POST['bulk_action']=='draft'){
    mysqli_query($k,"UPDATE publications SET status='draft' WHERE id IN ($ids)");
  }

  echo "<script>alert('Aksi berhasil');location='?hal=publications';</script>";
}
?>

<style>
/* =====================
   GLOBAL ADMIN STYLE
===================== */
body {
  background-color: #f5f6f8 !important;
}

.container-fluid {
  background: #ffffff !important;
  border-radius: 12px !important;
  padding: 20px !important;
  box-shadow: 0 8px 24px rgba(0,0,0,.08) !important;
}

/* =====================
   HEADER
===================== */
h3 {
  font-size: 1.6rem !important;
  letter-spacing: .5px !important;
}

/* =====================
   FORM & INPUT
===================== */
.form-control,
.form-select {
  height: 42px !important;
  border-radius: 8px !important;
  border: 1px solid #ddd !important;
  box-shadow: none !important;
}

.form-control:focus,
.form-select:focus {
  border-color: #dc3545 !important;
  box-shadow: 0 0 0 2px rgba(220,53,69,.15) !important;
}

/* =====================
   BUTTON
===================== */
.btn {
  border-radius: 8px !important;
  padding: 8px 14px !important;
  font-weight: 500 !important;
}

.btn-dark {
  background: #212529 !important;
}

.btn-warning {
  color: #000 !important;
}

/* =====================
   TABLE
===================== */
.table {
  margin-top: 10px !important;
}

.table thead th {
  font-size: .85rem !important;
  text-transform: uppercase !important;
  letter-spacing: .5px !important;
  text-align: center !important;
}

.table tbody td {
  vertical-align: middle !important;
  font-size: .9rem !important;
}

.table tbody tr:hover {
  background-color: #f8f9fa !important;
}

/* =====================
   CHECKBOX
===================== */
input[type="checkbox"] {
  width: 16px !important;
  height: 16px !important;
  cursor: pointer !important;
}

/* =====================
   BADGE STATUS
===================== */
.badge {
  padding: 6px 10px !important;
  font-size: .75rem !important;
  border-radius: 6px !important;
}

/* =====================
   BULK ACTION
===================== */
select[name="bulk_action"] {
  min-width: 160px !important;
}

/* =====================
   PAGINATION
===================== */
.pagination .page-link {
  color: #212529 !important;
  border-radius: 6px !important;
  margin: 0 3px !important;
  border: 1px solid #ddd !important;
}

.pagination .page-item.active .page-link {
  background: #dc3545 !important;
  border-color: #dc3545 !important;
  color: #fff !important;
}

.pagination .page-link:hover {
  background: #f1f1f1 !important;
}

/* =====================
   INFO TEXT
===================== */
.text-muted {
  font-size: .9rem !important;
}

/* =====================
   RESPONSIVE FIX
===================== */
@media(max-width:768px){
  .table thead {
    display: none !important;
  }
  .table tbody tr {
    display: block !important;
    margin-bottom: 15px !important;
    border: 1px solid #ddd !important;
    border-radius: 8px !important;
    padding: 10px !important;
  }
  .table tbody td {
    display: flex !important;
    justify-content: space-between !important;
    padding: 6px 10px !important;
    border: none !important;
  }
}
</style>
<div class="container-fluid py-4">

<h3 class="fw-bold mb-3">Daftar Publications</h3>

<!-- FILTER -->
<form method="get" class="row g-2 mb-3">
<input type="hidden" name="hal" value="publications">

<div class="col-md-2">
<select name="category" class="form-select" onchange="this.form.submit()">
  <option value="all">Semua Kategori</option>
  <option value="Education">Education</option>
  <option value="Research">Research</option>
</select>
</div>

<div class="col-md-3">
<input type="text" name="search" class="form-control"
placeholder="Cari judul..." value="<?= htmlspecialchars($search) ?>">
</div>

<div class="col-md-2">
<select name="limit" class="form-select" onchange="this.form.submit()">
<?php foreach([10,25,50,100] as $l): ?>
<option value="<?= $l ?>" <?= $limit==$l?'selected':'' ?>><?= $l ?> data</option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-5 text-end">
<a href="?hal=publications_tambah" class="btn btn-dark">
+ Tambah Publication
</a>
</div>
</form>

<p class="text-muted">
Menampilkan <?= $offset+1 ?>–<?= min($offset+$limit,$totalData) ?> dari <?= $totalData ?> data
</p>

<!-- TABLE -->
<form method="post">
<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
<th><input type="checkbox" id="checkAll"></th>
<th>Judul</th>
<th>Kategori</th>
<th>Tanggal</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($p=mysqli_fetch_assoc($data)): ?>
<tr>
<td><input type="checkbox" name="pub_id[]" value="<?= $p['id'] ?>"></td>
<td><?= htmlspecialchars($p['title']) ?></td>
<td><?= $p['category'] ?></td>
<td><?= date('d M Y',strtotime($p['created_at'])) ?></td>
<td>
<span class="badge bg-<?= $p['status']=='publish'?'success':'secondary' ?>">
<?= $p['status'] ?>
</span>
</td>
<td class="text-center">
  <a href="../uploads/publications/pdf/<?= htmlspecialchars($p['file']) ?>"
   target="_blank"
   class="btn btn-sm btn-primary mb-1">
   Preview
</a>

<a href="../uploads/publications/pdf/<?= htmlspecialchars($p['file']) ?>"
   class="btn btn-sm btn-success mb-1"
   download>
   Download
</a>

<br>

<a href="?hal=publications_edit&id=<?= $p['id'] ?>"
   class="btn btn-sm btn-warning">
   Edit
</a>

</td>
</tr>
<?php endwhile ?>
</tbody>
</table>
</div>

<!-- BULK ACTION -->
<div class="d-flex gap-2 mt-3">
<select name="bulk_action" class="form-select w-auto" required>
  <option value="">Pilih Aksi</option>
  <option value="publish">Publish</option>
  <option value="draft">Draft</option>
  <option value="delete">Hapus</option>
</select>
<button class="btn btn-danger">Terapkan</button>
</div>
</form>

<!-- PAGINATION -->
<nav class="mt-4">
<ul class="pagination justify-content-end">
<?php for($i=1;$i<=$totalPage;$i++): ?>
<li class="page-item <?= $i==$page?'active':'' ?>">
<a class="page-link"
href="?hal=publications&page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
</li>
<?php endfor ?>
</ul>
</nav>

</div>


<script>
document.addEventListener('show.bs.modal', function (event) {
  if (event.target.id === 'previewModal') {

    const button = event.relatedTarget;
    const pdfUrl = button.getAttribute('data-pdf');

    document.getElementById('pdfViewer').src = pdfUrl;
    document.getElementById('downloadBtn').href = pdfUrl;
  }
});

// reset iframe saat modal ditutup
document.getElementById('previewModal')
  .addEventListener('hidden.bs.modal', function () {
    document.getElementById('pdfViewer').src = '';
});
</script>

