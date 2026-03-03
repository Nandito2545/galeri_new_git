<style>
  .bgwhite {
    background-color: #f4f5f8;
  }
  .related-link{
    font-family: 'Playfair Display', serif;
  }
</style>

<!-- SECTION: Title -->
<section id="title-section">
  <div class="container">
    <h1>Penguatan Kerangka Regulasi dan Kebijakan HAM</h1>
  </div>
</section>

<!-- SECTION: AI Achievements (renamed for context) -->
<section id="crypto-achievements">
  <div class="container custom-container">
    <div class="section-heading">Langkah Strategis</div>

    <div class="achievement-item">
      <div class="number">01</div>
      <div>
        <p>Melanjutkan pembahasan RUU terkait Masyarakat Adat, sebagai pengakuan dan jaminan pelindungan bagi Masyarakat Adat.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">02</div>
      <div>
        <p>Melanjutkan pembahasan RUU Perlindungan Pekerja Rumah Tangga, sebagai jaminan pelindungan bagi kelompok Pekerja Rumah Tangga.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">03</div>
      <div>
        <p>Memprioritaskan pembahasan RUU Kitab Undang-undang Hukum Acara Pidana, sebagai tindak lanjut reformasi di bidang sistem peradilan pidana.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">04</div>
      <div>
        <p>Memprioritaskan revisi UU Bantuan Hukum, sebagai upaya untuk meningkatkan akses bantuan hukum dan peningkatan kualitas pelayanan bantuan hukum bagi masyarakat.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">05</div>
      <div>
        <p>Mendorong pembentukan UU lain yang penting bagi perlindungan hak asasi manusia dan peran masyarakat dalam pembangunan, antara lain UU Anti Diskriminasi yang Komprehensif dan UU Partisipasi Publik.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">06</div>
      <div>
        <p>Mempercepat proses ratifikasi instrumen-instrumen internasional hak asasi manusia yang diperlukan, seperti: Konvensi Perlindungan Bagi Semua Orang Dari Penghilangan Paksa, Protokol Pilihan untuk Konvensi Anti Penyiksaan, Konvensi tentang Pengungsi 1951, dan Statuta Roma 1998 tentang Mahkamah Pidana Internasional.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION: Related Links -->
<?php
include 'admin/koneksi.php';
$artikel = [];
$query = mysqli_query($k, "SELECT id, judul, tanggal FROM artikel WHERE status = 'publish' ORDER BY jumlah_dilihat DESC");
while ($row = mysqli_fetch_assoc($query)) {
  $artikel[] = $row;
}
?>

<section id="related-links" class="py-5 bg-white">
  <div class="container related-container">
    <h6 class="related-title">ARIKEL TERATAS</h6>

    <div id="terpopuler-container"></div>

    <nav class="mt-4">
      <ul class="pagination justify-content-center" id="pagination-nav"></ul>
    </nav>
  </div>
</section>

<script>
  const artikelData = <?= json_encode($artikel) ?>;
  const itemsPerPage = 3;
  let currentPage = 1;

  function renderArtikel(page) {
    const container = document.getElementById('terpopuler-container');
    container.innerHTML = '';
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedItems = artikelData.slice(start, end);

    paginatedItems.forEach(row => {
      const div = document.createElement('div');
      div.className = 'related-item mb-4';
      div.innerHTML = `
        <a href="artikel-detail?id=${row.id}" class="related-link">${row.judul}</a>
        <div class="related-date">${formatDate(row.tanggal)}</div>
      `;
      container.appendChild(div);
    });
  }

  function formatDate(dateStr) {
    const bulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
                   'August', 'September', 'October', 'November', 'December'];
    const date = new Date(dateStr);
    return `${date.getDate()} ${bulan[date.getMonth()]} ${date.getFullYear()}`;
  }

  function renderPagination() {
    const totalPages = Math.ceil(artikelData.length / itemsPerPage);
    const nav = document.getElementById('pagination-nav');
    nav.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement('li');
      li.className = 'page-item' + (i === currentPage ? ' active' : '');
      li.innerHTML = `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
      li.querySelector('a').addEventListener('click', function (e) {
        e.preventDefault();
        currentPage = parseInt(this.getAttribute('data-page'));
        renderArtikel(currentPage);
        renderPagination();
      });
      nav.appendChild(li);
    }
  }

  // Initial load
  renderArtikel(currentPage);
  renderPagination();
</script>
