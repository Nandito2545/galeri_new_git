@include('components.user_navbar')

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

<style>
    /* Animasi Fade & Slide */
    .slide-wrapper {
        position: relative;
        min-height: 80vh; /* Agar container tidak mengecil saat transisi */
    }
    .slide-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        opacity: 0;
        visibility: hidden;
        transform: translateX(50px);
        transition: all 0.6s ease-in-out;
    }
    .slide-item.active {
        position: relative;
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    .slide-item.slide-left {
        transform: translateX(-50px);
    }
    
    /* Memastikan gambar seragam dan bagus */
    .feature-card {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    .feature-card:hover {
        transform: translateY(-5px);
    }
    .feature-card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    .feature-title {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
    .feature-card .badge-top {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 2;
    }
</style>

<section class="edge-features-section overflow-hidden" style="padding-top: 80px;">
  <div class="container-fluid slide-wrapper">
    
    <!-- SLIDE 1 -->
    <div class="row align-items-center slide-item active" id="slide-1">
      <div class="col-lg-4 edge-left pe-lg-5">
        <h1>Kategori Utama<br><span>Eksplorasi Konten</span></h1>
        <p>
          Temukan berbagai kategori konten menarik kami. Klik pada kotak kategori di samping untuk menjelajahi cerita, inspirasi, dan buku-buku terbaik pilihan kami.
        </p>

        <a href="{{ route('home') }}" class="btn btn-light btn-more mb-4">Back to Home</a>

        <div class="edge-nav">
          <button class="btn nav-btn prev-btn" style="opacity:0.5; cursor:not-allowed;">←</button>
          <button class="btn nav-btn active next-btn" onclick="changeSlide(2)">Next →</button>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="row g-4">
          <!-- Kategori 1-6 -->
          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'buku']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800&auto=format&fit=crop" alt="Buku">
                  <div class="feature-title">Buku</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'kata']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1455390582262-044cdead27d8?q=80&w=800&auto=format&fit=crop" alt="Kata">
                  <div class="feature-title">Kata</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'kota']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1514565131-fce0801e5785?q=80&w=800&auto=format&fit=crop" alt="Kota">
                  <div class="feature-title">Kota</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'inspirasi']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1499209974431-9dddcece7f88?q=80&w=800&auto=format&fit=crop" alt="Inspirasi">
                  <div class="feature-title">Inspirasi</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'gairah']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=800&auto=format&fit=crop" alt="Gairah">
                  <div class="feature-title">Gairah</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'cerita']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1519682337058-a94d519337bc?q=80&w=800&auto=format&fit=crop" alt="Cerita">
                  <div class="feature-title">Cerita</div>
                </div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- SLIDE 2 -->
    <div class="row align-items-center slide-item" id="slide-2">
      <div class="col-lg-4 edge-left pe-lg-5">
        <h1>Koleksi Publikasi<br><span>& Galeri Spesial</span></h1>
        <p>
          Lanjutkan perjalanan Anda dengan membaca majalah eksklusif, e-book pilihan, serta menikmati koleksi galeri visual yang memanjakan mata.
        </p>

        <a href="{{ route('home') }}" class="btn btn-light btn-more mb-4">Back to Home</a>

        <div class="edge-nav">
          <button class="btn nav-btn active prev-btn" onclick="changeSlide(1)">← Prev</button>
          <button class="btn nav-btn next-btn" style="opacity:0.5; cursor:not-allowed;">Next →</button>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="row g-4">
          
          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'poetri-magz']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card border border-warning shadow-lg" style="transform: scale(1.02);">
                  <span class="badge-top">⭐ Prioritas</span>
                  <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?q=80&w=800&auto=format&fit=crop" alt="Poetri Magz">
                  <div class="feature-title text-warning">Poetri Magz</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'coffeshopia-magz']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?q=80&w=800&auto=format&fit=crop" alt="Coffeshopia Magz">
                  <div class="feature-title">Coffeshopia Magz</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'e-book']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1526481280693-3bfa7568e0f3?q=80&w=800&auto=format&fit=crop" alt="E-Book">
                  <div class="feature-title">E-Book</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'essai']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1455390582262-044cdead27d8?q=80&w=800&auto=format&fit=crop" alt="Essai">
                  <div class="feature-title">Essai</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'pustaka']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=800&auto=format&fit=crop" alt="Pustaka">
                  <div class="feature-title">Pustaka</div>
                </div>
            </a>
          </div>

          <div class="col-md-4 col-sm-6">
            <a href="{{ route('fitur.detail', ['slug' => 'galeri']) ?? '#' }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://images.unsplash.com/photo-1501084817091-a4f3d1d19e07?q=80&w=800&auto=format&fit=crop" alt="Galeri">
                  <div class="feature-title">Galeri</div>
                </div>
            </a>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>

<script>
function changeSlide(targetIndex) {
    const slide1 = document.getElementById('slide-1');
    const slide2 = document.getElementById('slide-2');

    if(targetIndex === 2) {
        // Pindah ke slide 2 (animasi dari kanan ke kiri)
        slide1.classList.remove('active');
        slide1.classList.add('slide-left');
        
        slide2.classList.remove('slide-left');
        slide2.classList.add('active');
    } else {
        // Pindah ke slide 1 (animasi dari kiri ke kanan)
        slide2.classList.remove('active');
        slide2.classList.add('slide-left');
        
        slide1.classList.remove('slide-left');
        slide1.classList.add('active');
    }
}
</script>