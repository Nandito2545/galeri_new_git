<style>
  .bg-white {
    background-color: #f4f5f8 !important;
  }
  .related-link{
    font-family: 'Playfair Display', serif;
  }
</style>

<!-- SECTION: Title -->
<section id="title-section">
  <div class="container">
    <h1>Peningkatan Pemenuhan Hak-Hak Ekonomi, Sosial, dan Budaya</h1>
  </div>
</section>

<!-- SECTION: AI Achievements -->
<section id="ai-achievements">
  <div class="container custom-container">
    <div class="section-heading">Fokus Kerja</div>

    <div class="achievement-item">
      <div class="number">01</div>
      <div>
        <p>Terlaksananya secara efektif pemenuhan hak-hak ekonomi, sosial dan budaya dalam Visi-Misi Bersama Indonesia Maju menuju Indonesia Emas 2045.</p>
      </div>
    </div>

    <div class="achievement-item">
      <div class="number">02</div>
      <div>
        <p>Penguatan pemenuhan hak-hak ekonomi, sosial, dan budaya dengan meningkatkan akses, ketersediaan, keterjangkauan, dan kualitas pelayanan. Termasuk menerapkan prinsip non-diskriminasi.</p>
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
