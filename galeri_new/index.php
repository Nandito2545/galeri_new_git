<?php
session_start();

/* ======================
   CEK LOGIN
====================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

include 'admin/koneksi.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';

// Hindari akses file di luar folder user/
$page = basename($page);

// Fungsi mencari file HTML/PHP di folder user/
function findFile($baseDir, $target) {
  $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
  foreach ($rii as $file) {
    if ($file->isDir()) continue;

    // Ambil nama file tanpa ekstensi
    $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

    // Cocokkan dengan target
    if (strtolower($filename) === strtolower($target)) {
      return $file->getPathname();
    }
  }
  return false;
}

$contentFile = findFile('user', $page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AEI Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="user/assets/css/style.css">
    <link rel="stylesheet" href="user/assets/css/responsive.css">
</head>
<body>
<main>
  <?php
    if (isset($_GET['q']) && $_GET['q'] !== '') {
      $q = mysqli_real_escape_string($k, $_GET['q']);
      $result = mysqli_query($k, "SELECT * FROM artikel WHERE judul LIKE '%$q%' ORDER BY tanggal DESC");

      if (mysqli_num_rows($result) > 0) {
        echo "<section id='all-news' class='py-5'><div class='container'><div class='berita'>";
        while ($a = mysqli_fetch_assoc($result)) {
          echo "<div class='row mb-4 align-items-center pb-4 border-bottom'>
            <div class='col-md-7'>
              <a href='artikel-detail?id=" . $a['id'] . "' class='text-decoration-none text-dark'>
                <small class='fw-bold'>" . ucfirst($a['menu']) . "</small>
                <h4 class='fw-bold my-2'>" . htmlspecialchars($a['judul']) . "</h4>
                <p class='text-secondary'>" . htmlspecialchars(mb_substr($a['deskripsi_artikel'], 0, 120)) . "...</p>
              </a>
            </div>
            <div class='col-md-5'>
              <a href='artikel-detail?id=" . $a['id'] . "'>
                <img src='img/" . $a['gambar'] . "' alt='" . htmlspecialchars($a['judul']) . "' class='img-fluid' style='max-height: 200px; width: 400px; object-fit: cover;'>
              </a>
            </div>
          </div>";
        }
        echo "</div></div></section>";
      } else {
        echo "<div class='alert alert-warning'>Tidak ditemukan artikel yang sesuai dengan kata kunci.</div>";
      }
    } elseif ($contentFile && file_exists($contentFile)) {
      include $contentFile;
    } else {
      echo "<h2 class='text-center text-danger'>Halaman tidak ditemukan</h2>";
    }
  ?>
</main>
<script src="user/assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>