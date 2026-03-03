<style>
  .related-link{
    font-family: 'Playfair Display', serif;
  }
</style>
<section id="title-section">
  <div class="container">
    <h1>Pelindungan Hak-Hak Perempuan, Anak, Penyandang Disabilitas, dan Kelompok Rentan Lainnya</h1>
  </div>
</section>

<section id="crypto-achievements">
  <div class="container custom-container">
    <div class="section-heading">Pelindungan Kelompok Rentan</div>

    <div class="achievement-item">
      <div class="number">01</div>
      <div>
        <p>Terlaksananya secara efektif program-program perlindungan Perempuan, Anak, Penyandang Disabilitas, dan kelompok rentan lainnya dalam Visi-Misi Bersama Indonesia Maju menuju Indonesia Emas 2045.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">02</div>
      <div>
        <p>Pembentukan Badan Dana Bantuan Korban sebagai mandat penting UU Nomor 12 Tahun 2022 tentang Tindak Pidana Kekerasan Seksual (TPKS).</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">03</div>
      <div>
        <p>Penguatan dukungan dan sumberdaya bagi Komnas Perempuan untuk implementasi UU TPKS.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">04</div>
      <div>
        <p>Untuk mengimplementasikan agenda penegakan hukum terhadap TPKS, perlu juga dilakukan peningkatan kapasitas bagi Aparat Penegak Hukum dalam penanganan TPKS.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">05</div>
      <div>
        <p>Untuk pelindungan kelompok-kelompok rentan dan masyarakat dari tindakan diskriminatif, perlu ada kebijakan hukum yang kuat dengan membentuk UU Anti-Diskriminasi yang Komprehensif.</p>
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
