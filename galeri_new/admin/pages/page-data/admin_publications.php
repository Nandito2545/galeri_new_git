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

  if($_POST['bulk_action']=='delete'){
    mysqli_query($k,"DELETE FROM publications WHERE id IN ($ids)");
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
