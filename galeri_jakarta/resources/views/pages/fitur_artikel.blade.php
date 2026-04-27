<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kategori }}</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

        .editorial-fixed {
            height: 100vh;
            font-family: "Playfair Display", serif;
            overflow: hidden;
            display: flex;
        }

        /* LEFT */
        .left-area {
            width: 65%;
            margin-top: 4%;
            padding: 0px 20px;
        }

        /* HERO */
        .hero-fixed {
            position: relative;
            height: 60%;
        }

        .hero-fixed img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-title-fixed {
            position: absolute;
            top: 35px;
            left: 35px;
            background: #7f9484;
            color: #fff;
            padding: 22px 28px;
            font-size: 38px;
            line-height: 1.15;
            max-width: 80%;
        }

        .hero-tag-fixed {
            position: absolute;
            bottom: -25px;
            left: 0px;
            background: #7f9484;
            color: #fff;
            padding: 15px 20px;
            font-size: 18px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-dots-fixed {
            position: absolute;
            bottom: 26px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .hero-dots-fixed span {
            width: 10px;
            height: 10px;
            background: #b46a45;
            border-radius: 50%;
        }

        /* THUMBS */
        .thumbs-fixed {
            display: flex;
            margin-top: 6%;
            gap: 0;
            overflow-x: auto;
        }
        .thumb-item{
            padding: 10px 15px;
            border-right: 1px solid black;
            flex: 0 0 20%;
        }
        .thumb-item:last-child {
            border-right: none;
        }
        .thumb-item img {
            width: 100%;
            height: 110px;
            object-fit: cover;
        }
        .thumb-item p {
            font-size: 14px;
            margin: 6px 0 2px;
            font-weight: bold;
        }
        .thumb-item small {
            color: #b24;
            font-size: 12px;
            text-transform: uppercase;
        }

        /* RIGHT */
        .right-area {
            width: 34%;
            margin-top: 4%;
            padding: 0px 40px;
            position: relative;
            overflow-y: auto;
        }

        .side-article {
            display: flex;
            gap: 14px;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid black;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        .side-article:hover {
            opacity: 0.7;
        }

        .side-article img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .side-article h6 {
            font-size: 15px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .side-article span {
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #555;
        }

        .right-area hr {
            margin: 20px 0;
        }

        .side-nav-fixed {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
        }

        /* LINGKARAN */
        .side-nav-fixed i {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #222;
            border-radius: 50%;
            font-size: 20px;
            color: #222;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        /* HOVER */
        .side-nav-fixed i:hover {
            background: #222;
            color: #fff;
            transform: scale(1.08);
        }

        /* STRIP */
        .color-strip {
            width: 2%;
            display: flex;
            flex-direction: column;
        }
        .color-strip .brown {
            height: 70%;
            background: #7a3f21;
        }
        .color-strip .green {
            height: 30%;
            background: #7f9484;
        }

        @media (max-width: 768px) {
            .editorial-fixed { flex-direction: column; height: auto; overflow: auto; }
            .left-area, .right-area, .color-strip { width: 100%; padding: 20px; margin-top: 0; }
            .color-strip { display: none; }
            .side-nav-fixed { position: relative; margin-top: 20px; bottom: 0; }
            .hero-title-fixed { font-size: 24px; padding: 15px; }
            .hero-fixed { height: 300px; margin-bottom: 40px; }
        }
    </style>
</head>
<body>

@include('components.user_navbar')

<a href="{{ route('beranda') }}" style="position:fixed; top:20px; left:20px; z-index:9999; color:#fff; text-decoration:none; font-family:sans-serif; background:rgba(0,0,0,0.8); padding:8px 12px; border-radius:5px;">← Kembali</a>

<section class="editorial-fixed">
    
    <!-- LEFT -->
    <div class="left-area">
        @php
            $hero = $artikels->first();
            $thumbs = $artikels->slice(1, 5); // Ambil 5 data setelah hero
            $sides = $artikels->slice(6, 4); // Ambil sisanya untuk samping
        @endphp

        <!-- HERO -->
        <div class="hero-fixed">
            @if($hero)
                <img src="{{ $hero->thumbnail ? asset('img/' . $hero->thumbnail) : 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e' }}">
                <div class="hero-title-fixed">
                    {{ Str::limit($hero->judul, 50) }}
                </div>
            @else
                <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e">
                <div class="hero-title-fixed">Belum Ada Artikel</div>
            @endif

            <div class="hero-dots-fixed">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
            <div class="hero-tag-fixed">{{ strtoupper($kategori) }}</div>
        </div>

        <!-- THUMBS -->
        <div class="thumbs-fixed">
            @forelse($thumbs as $thumb)
            <div class="thumb-item">
                <img src="{{ $thumb->thumbnail ? asset('img/' . $thumb->thumbnail) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d' }}">
                <p>{{ Str::limit($thumb->judul, 30) }}</p>
                <small>BY: GALERI JAKARTA</small>
            </div>
            @empty
                <div class="p-3 text-muted">Belum ada artikel tambahan.</div>
            @endforelse
        </div>

    </div>

    <!-- RIGHT -->
    <aside class="right-area">
        @forelse($sides as $side)
        <div class="side-article">
            <img src="{{ $side->thumbnail ? asset('img/' . $side->thumbnail) : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2' }}">
            <div>
                <h6>{{ Str::limit($side->judul, 40) }}</h6>
                <span>BY GALERI JAKARTA</span>
            </div>
        </div>
        @empty
            @if($artikels->count() > 1 && $sides->count() == 0)
                <div class="p-3 text-muted">Tidak ada artikel lainnya.</div>
            @elseif($artikels->count() == 0)
                <div class="p-3 text-muted">Data masih kosong.</div>
            @endif
        @endforelse

        <div class="side-nav-fixed">
            <i class="bi bi-chevron-left"></i>
            <i class="bi bi-chevron-right"></i>
        </div>
    </aside>

    <!-- STRIPS -->
    <div class="color-strip">
        <div class="brown"></div>
        <div class="green"></div>
    </div>

</section>

</body>
</html>
