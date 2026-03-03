<?php
include '../admin/koneksi.php';

$q = isset($_GET['q']) ? mysqli_real_escape_string($k, $_GET['q']) : '';

$data = [];

if (strlen($q) >= 2) {
  $query = "SELECT id, judul, gambar FROM artikel 
            WHERE judul LIKE '%$q%' 
            ORDER BY jumlah_dilihat DESC 
            LIMIT 3";
  $result = mysqli_query($k, $query);
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
}

header('Content-Type: application/json');
echo json_encode($data);
