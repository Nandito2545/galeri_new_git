<section id="artikel1">
  <div class="container">
    <div class="head">
      <?php
      $id1 = intval($_GET['id']);

      // Hitung view
      $result = mysqli_query($k, "SELECT jumlah_dilihat FROM artikel WHERE id = '$id1'");
      $row = mysqli_fetch_assoc($result);
      $new_views = $row['jumlah_dilihat'] + 1;
      mysqli_query($k, "UPDATE artikel SET jumlah_dilihat = $new_views WHERE id = '$id1'");

      // Ambil data artikel
      $sql1 = "SELECT * FROM artikel WHERE id = '$id1' LIMIT 1";
      $q1 = mysqli_query($k, $sql1);
      $article_url = "https://mugiyanto.id/artikel-detail?id=$id1";

      if ($q1 && mysqli_num_rows($q1) > 0):
        while ($r1 = mysqli_fetch_assoc($q1)):
          $gambar_array = json_decode($r1['gambar'], true);
          $deskripsi_array = json_decode($r1['deskripsi_gambar'], true);
          $gambar_pertama = !empty($gambar_array) ? trim($gambar_array[0]) : 'default.jpg';
          $deskripsi_pertama = !empty($deskripsi_array) ? trim($deskripsi_array[0]) : 'default';
      ?>
      <div class="img" style="background: url('img/<?=$r1['gambar']?>'); background-size:cover; background-position:center;"></div>
      <h3 class="desc">
        <img src="img/logodesc.png" alt="Logo"> <?=$r1['deskripsi_gambar']?>
      </h3>
      <div class="garis"></div>
      <div class="waktu">
        <p class="detail"><?= strtoupper($r1['menu']) ?></p>
        <p class="tgl"><?=$r1['tanggal']?></p>
        <p class="sponsor"><?=$r1['penulis']?></p>
      </div>
      <h3 class="title" style="font-family: 'Playfair Display', serif;"><?=$r1['judul']?></h3>
      <p class="lead" style="font-family: 'Playfair Display', serif;"><?=$r1['deskripsi_artikel']?></p>
    </div>
  </div>

  <div class="container">
    <div class="paragraf">
      <div class="row">
        <div class="sosmed">
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="https://x.com/intent/tweet?text=<?=urlencode($r1['judul'])?>&url=<?=urlencode($article_url)?>" target="_blank" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($article_url)?>" target="_blank" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
        </div>
      </div>

      <div class="row">
        <h3 class="penulis" style="font-family: 'Playfair Display', serif;">By <?=$r1['penulis']?></h3>
        <div class="isi-artikel" style="font-family: 'Playfair Display', serif;">
            <?php
            $paragraf_array = explode("\n", $r1['isi']);
            foreach ($paragraf_array as $paragraf):
                echo "<p>" . trim($paragraf) . "</p>";
            endforeach;
            ?>
        </div>

      </div>
    </div>
  </div>
  <?php endwhile; endif; ?>
</section>

<!-- Read More Section -->
<section id="readmore" class="py-5">
  <div class="container">
    <div id="artikelContainer" class="row g-4">
      <?php
      $q_slider = mysqli_query($k, "SELECT * FROM artikel WHERE id != '$id1' ORDER BY jumlah_dilihat DESC");

      $artikel_list = [];
      while ($artikel = mysqli_fetch_assoc($q_slider)) {
        $artikel_list[] = $artikel;
      }

      // Gunakan 4 per page default (untuk desktop)
      $perPage = 4;
      $totalPage = ceil(count($artikel_list) / $perPage);
      foreach ($artikel_list as $i => $a):
        $pageIndex = floor($i / $perPage);
      ?>
        <div class="col-6 col-md-3 artikel-item" data-index="<?= $i ?>" data-page="<?= $pageIndex ?>" style="<?= $pageIndex === 0 ? '' : 'display:none;' ?>">
          <div class="card h-100">
            <a href="artikel-detail?id=<?= $a['id'] ?>" class="text-decoration-none text-dark">
              <img src="img/<?= $a['gambar'] ?>" class="card-img-top" alt="<?= htmlspecialchars($a['judul']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($a['judul']) ?></h5>
              </div>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination Bootstrap -->
    <nav aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center" id="pagination">
        <?php for ($i = 0; $i < $totalPage; $i++): ?>
          <li class="page-item <?= $i === 0 ? 'active' : '' ?>">
            <button class="page-link" data-page="<?= $i ?>"><?= $i + 1 ?></button>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>
</section>


<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const slider = document.querySelector('.slider');
  const slides = Array.from(document.querySelectorAll('.slide'));
  const prevButton = document.getElementById('prev');
  const nextButton = document.getElementById('next');

  slides.forEach(slide => {
    slider.appendChild(slide.cloneNode(true));
    slider.insertBefore(slide.cloneNode(true), slider.firstChild);
  });

  const totalSlides = slider.children.length;
  let currentIndex = slides.length;
  let slideWidth = slides[0].offsetWidth;

  slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

  function showSlide(index) {
    slider.style.transition = 'transform 0.5s ease-in-out';
    slider.style.transform = `translateX(-${index * slideWidth}px)`;
  }

  function showNextSlide() {
    currentIndex++;
    showSlide(currentIndex);
    if (currentIndex >= totalSlides - slides.length) {
      setTimeout(() => {
        slider.style.transition = 'none';
        currentIndex = slides.length;
        slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
      }, 500);
    }
  }

  function showPrevSlide() {
    currentIndex--;
    showSlide(currentIndex);
    if (currentIndex < slides.length) {
      setTimeout(() => {
        slider.style.transition = 'none';
        currentIndex = totalSlides - slides.length - 1;
        slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
      }, 500);
    }
  }

  nextButton.addEventListener('click', showNextSlide);
  prevButton.addEventListener('click', showPrevSlide);

  window.addEventListener('resize', () => {
    slideWidth = slides[0].offsetWidth;
    slider.style.transition = 'none';
    slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  });
});

// Format tanggal
const articleMeta = document.querySelector('.tgl');
if (articleMeta) {
  const dateText = articleMeta.textContent;
  const date = new Date(dateText);
  function formatDate(date) {
    const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    return `${months[date.getMonth()]}, ${date.getDate()}, ${date.getFullYear()}`;
  }
  articleMeta.textContent = formatDate(date);
}
document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.artikel-item');
  const paginationButtons = document.querySelectorAll('#pagination .page-link');

  // Hitung perPage sesuai layar
  function getPerPage() {
    return window.innerWidth <= 480 ? 2 : 4; // 2 kartu per halaman di HP, 4 di desktop
  }

  // Set tampilan halaman sesuai index
  function setPage(page) {
    const perPage = getPerPage();
    items.forEach((item, index) => {
      const itemPage = Math.floor(index / perPage);
      item.style.display = (itemPage === page) ? 'block' : 'none';
    });

    paginationButtons.forEach(btn => btn.parentElement.classList.remove('active'));
    paginationButtons[page].parentElement.classList.add('active');
  }

  paginationButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const page = parseInt(btn.dataset.page);
      setPage(page);
    });
  });

  // Tampilkan halaman 0 saat load
  setPage(0);

  // Jika ukuran layar berubah, sesuaikan tampilan
  window.addEventListener('resize', () => {
    const activeBtn = document.querySelector('#pagination .page-item.active .page-link');
    if (activeBtn) {
      setPage(parseInt(activeBtn.dataset.page));
    }
  });
});
</script>
