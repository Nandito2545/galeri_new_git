<?php
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

<nav class="navbar aei-navbar fixed-top px-4">
  <div class="container-fluid d-flex align-items-center justify-content-between">

    <div class="d-flex gap-2">
      <button class="btn btn-subscribe">SUBSCRIBE</button>
      <button class="btn btn-donate">DONATE</button>
    </div>

    <div class="navbar-brand mx-auto"></div>

    <div class="d-flex align-items-center gap-3">
      <div class="search-box">
        <input type="text" placeholder="Search">
        <i class="bi bi-search"></i>
      </div>

      <i class="bi bi-list menu-icon"
        id="menuToggle"
        data-bs-toggle="offcanvas"
        data-bs-target="#menuOffcanvas">
      </i>

    </div>

  </div>
</nav>
<div class="offcanvas aei-offcanvas"
     tabindex="1000"
     id="menuOffcanvas">

  <div class="offcanvas-body p-4">
    <div class="container-fluid h-100">
      <div class="row h-100">

        <!-- LEFT -->
        <div class="col-md-4 menu-left">
          <h6 class="menu-title">Menu</h6>
          <ul class="menu-list">
            <li><a href="our-expert">Our Expert</a></li>
            <li><a href="policy-brief">Policy Brief</a></li>
            <li><a href="pemikiran">Pemikiran</a></li>
            <li><a href="program">Program</a></li>
            <li><a href="#">Courses</a></li>
            <li><a href="tentang">Tentang</a></li>
            <li><a href="#">Galeri Foto</a></li>
            <li><a href="#">Podcast</a></li>
            <li><a href="kontak">Kontak</a></li>
          </ul>
        </div>

        <!-- RIGHT -->
        <div class="col-md-8 menu-right">
          <div class="row g-4">
            <div class="col-6">
              <img src="https://picsum.photos/500/350?1" class="img-fluid">
              <p class="menu-caption">
                Does Drinking Matcha Really Cause Hair Loss?
              </p>
            </div>

            <div class="col-6">
              <img src="https://picsum.photos/500/350?2" class="img-fluid">
              <p class="menu-caption">
                Sex Deserves a Place in Your Wellness Routine
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>




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
<footer class="footer-wd">
    <div class="container-fluid px-5">
        <div class="row align-items-center">

            <!-- Logo -->
            <div class="col-md-3 d-flex align-items-center">
                <div class="logo-box">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
                <span class="footer-logo-text">Women<br>Deliver</span>
            </div>

            <!-- Address -->
            <div class="col-md-3 footer-text">
                <p class="mb-1">515 Madison Avenue, 8th Floor,</p>
                <p class="mb-1">New York, NY 10022, USA</p>
                <p class="mb-1">Tel: +1.646.695.9100</p>
                <p class="mb-0">Email: <a href="#">info@womendeliver.org</a></p>
            </div>

            <!-- Social Media -->
            <div class="col-md-4 text-center footer-social">
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-medium"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-flickr"></i></a>
            </div>

            <!-- Contact & Donate -->
            <div class="col-md-2 text-end">
                <a href="work" class="footer-contact">Contact</a>
                <a href="subrice" class="btn btn-donate ms-3">Donate</a>
            </div>

        </div>
    </div>
</footer>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const offcanvasEl = document.getElementById('menuOffcanvas');
    const toggleIcon = document.getElementById('menuToggle');
    const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);

    /* ==========================
       ICON CHANGE
    ========================== */
    offcanvasEl.addEventListener('shown.bs.offcanvas', () => {
        toggleIcon.classList.remove('bi-list');
        toggleIcon.classList.add('bi-x');
        toggleIcon.classList.add('active');
    });

    offcanvasEl.addEventListener('hidden.bs.offcanvas', () => {
        toggleIcon.classList.remove('bi-x');
        toggleIcon.classList.add('bi-list');
        toggleIcon.classList.remove('active');
    });

    /* ==========================
       CLICK OUTSIDE TO CLOSE
    ========================== */
    document.addEventListener('click', function (e) {
        if (
            offcanvasEl.classList.contains('show') &&
            !offcanvasEl.contains(e.target) &&
            !toggleIcon.contains(e.target)
        ) {
            bsOffcanvas.hide();
        }
    });
});
</script>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>