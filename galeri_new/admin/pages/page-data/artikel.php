<?php
include "koneksi.php";

/* =====================
   PARAMETER
===================== */
$menu_filter  = $_GET['menu'] ?? 'all';
$search_judul = $_GET['judul'] ?? '';
$limit        = $_GET['limit'] ?? 10;
$page         = $_GET['page'] ?? 1;
$sort         = $_GET['sort'] ?? 'tanggal';

$limit = in_array($limit,[10,25,50,100]) ? (int)$limit : 10;
$page  = max(1,(int)$page);
$offset = ($page-1)*$limit;

/* =====================
   FILTER
===================== */
$where = [];

if($menu_filter!='all'){
  $where[] = "menu='".mysqli_real_escape_string($k,$menu_filter)."'";
}

if($search_judul){
  $where[] = "judul LIKE '%".mysqli_real_escape_string($k,$search_judul)."%'";
}

$whereSQL = $where ? "WHERE ".implode(" AND ",$where) : "";

/* =====================
   SORT
===================== */
$orderBy = $sort=='dilihat' ? 'jumlah_dilihat DESC' : 'tanggal DESC';

/* =====================
   TOTAL DATA
===================== */
$qTotal = mysqli_query($k,"SELECT COUNT(*) total FROM artikel $whereSQL");
$totalData = mysqli_fetch_assoc($qTotal)['total'];
$totalPage = ceil($totalData/$limit);

/* =====================
   DATA
===================== */
$data = mysqli_query($k,"
  SELECT * FROM artikel
  $whereSQL
  ORDER BY $orderBy
  LIMIT $limit OFFSET $offset
");

/* =====================
   BULK ACTION
===================== */

if(
  isset($_POST['bulk_action'], $_POST['artikel_id']) &&
  is_array($_POST['artikel_id'])
){

  // LIST ID AMAN
  $ids = implode(',', array_map('intval', $_POST['artikel_id']));

  /* =====================
     DELETE
  ===================== */
  if($_POST['bulk_action'] == 'delete'){

    // Ambil gambar dulu
    $qImg = mysqli_query($k,"
      SELECT gambar FROM artikel 
      WHERE id IN ($ids)
    ");

    while($img = mysqli_fetch_assoc($qImg)){
      if(!empty($img['gambar'])){
        $path = "../img/".$img['gambar'];
        if(file_exists($path)){
          unlink($path);
        }
      }
    }

    // Hapus data artikel
    mysqli_query($k,"
      DELETE FROM artikel 
      WHERE id IN ($ids)
    ");
  }

  /* =====================
     PUBLISH
  ===================== */
  if($_POST['bulk_action'] == 'publish'){
    mysqli_query($k,"
      UPDATE artikel 
      SET status='publish' 
      WHERE id IN ($ids)
    ");
  }

  /* =====================
     DRAFT
  ===================== */
  if($_POST['bulk_action'] == 'draft'){
    mysqli_query($k,"
      UPDATE artikel 
      SET status='draft' 
      WHERE id IN ($ids)
    ");
  }

  echo "<script>
    alert('Aksi berhasil');
    location='.?hal=artikel';
  </script>";
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

<h3 class="fw-bold mb-3">Daftar Artikel</h3>

<!-- FILTER -->
<form method="get" class="row g-2 mb-3">
<input type="hidden" name="hal" value="artikel">

<div class="col-md-2">
<select name="menu" class="form-select" onchange="this.form.submit()">
  <option value="all" <?= $menu_filter=='all'?'selected':'' ?>>Semua Menu</option>
  <option value="artikel" <?= $menu_filter=='artikel'?'selected':'' ?>>Artikel</option>
  <option value="esai" <?= $menu_filter=='esai'?'selected':'' ?>>Esai</option>
</select>
</div>

<div class="col-md-3">
<input type="text" name="judul" class="form-control"
placeholder="Cari judul..." value="<?= htmlspecialchars($search_judul) ?>">
</div>

<div class="col-md-2">
<select name="sort" class="form-select" onchange="this.form.submit()">
  <option value="tanggal" <?= $sort=='tanggal'?'selected':'' ?>>Terbaru</option>
  <option value="dilihat" <?= $sort=='dilihat'?'selected':'' ?>>Terbanyak Dilihat</option>
</select>
</div>

<div class="col-md-2">
<select name="limit" class="form-select" onchange="this.form.submit()">
<?php foreach([10,25,50,100] as $l): ?>
<option value="<?= $l ?>" <?= $limit==$l?'selected':'' ?>><?= $l ?> data</option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-3 text-end">
<a href=".?hal=artikel_tambah" class="btn btn-dark">+ Tambah Artikel</a>
</div>
</form>

<p class="text-muted">
Menampilkan <?= $offset+1 ?>–<?= min($offset+$limit,$totalData) ?> dari <?= $totalData ?> artikel
</p>

<!-- TABLE -->
<form method="post">
<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
<th><input type="checkbox" id="checkAll"></th>
<th>Judul</th>
<th>Tanggal</th>
<th>Status</th>
<th>Menu</th>
<th>Dilihat</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while($a=mysqli_fetch_assoc($data)): ?>
<tr>
<td><input type="checkbox" name="artikel_id[]" value="<?= $a['id'] ?>"></td>
<td><?= htmlspecialchars($a['judul']) ?></td>
<td><?= date('d M Y',strtotime($a['tanggal'])) ?></td>
<td><span class="badge bg-<?= $a['status']=='publish'?'success':'secondary' ?>"><?= $a['status'] ?></span></td>
<td><?= ucfirst($a['menu']) ?></td>
<td><?= $a['jumlah_dilihat'] ?></td>
<td>
<a href=".?hal=artikel_edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
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
href="?hal=artikel&menu=<?= $menu_filter ?>&judul=<?= urlencode($search_judul) ?>&limit=<?= $limit ?>&sort=<?= $sort ?>&page=<?= $i ?>">
<?= $i ?>
</a>
</li>
<?php endfor ?>
</ul>
</nav>

</div>

<script>
document.getElementById('checkAll').onclick = e => {
document.querySelectorAll('input[name="artikel_id[]"]').forEach(c=>c.checked=e.target.checked);
}
</script>
