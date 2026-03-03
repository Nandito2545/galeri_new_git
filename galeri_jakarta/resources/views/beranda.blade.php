    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
<section class="edge-features-section">
  <div class="container-fluid">
    <div class="row align-items-center">

      <div class="col-lg-4 edge-left">
        <h1>Explore the latest<br><span>features in Edge</span></h1>
        <p>
          Microsoft Edge introduces exciting new features every month.
          Click on a tile to explore some of the latest features and
          built-in tools.
        </p>

        <a href="{{ route('home') }}" class="btn btn-light btn-more">Back to Home</a>

        <div class="edge-nav">
          <button class="btn nav-btn">←</button>
          <button class="btn nav-btn active">Next →</button>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="row g-4">

          <div class="col-md-4">
            <a href="{{ route('fitur.detail', ['slug' => 'buku']) }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://picsum.photos/800/1000?random=1" alt="Buku">
                  <div class="feature-title">Buku</div>
                </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('fitur.detail', ['slug' => 'kata']) }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://picsum.photos/800/1000?random=2" alt="Kata">
                  <div class="feature-title">Kata</div>
                </div>
            </a>
          </div>

          <div class="col-md-4">
            <a href="{{ route('fitur.detail', ['slug' => 'kota']) }}" class="text-decoration-none">
                <div class="feature-card">
                  <img src="https://picsum.photos/800/1000?random=3" alt="Kota">
                  <div class="feature-title">Kota</div>
                </div>
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>