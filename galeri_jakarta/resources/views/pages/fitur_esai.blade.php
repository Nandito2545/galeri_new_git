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
        /* CSS ASLI DARI esai.php */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

        body, html {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden; /* disable scroll */
            font-family: 'Playfair Display', serif;
        }

        .editorial-section {
            height: 100vh;
            position: relative;
        }

        /* ====== KIRI / IMAGE ====== */
        .editorial-image {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }
        .editorial-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease-in-out;
        }

        /* Overlay */
        .image-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4); 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            padding: 20px;
        }
        .image-overlay .label {
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .image-overlay h2 {
            font-size: 3rem;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .image-overlay p.author {
            font-size: 1rem;
            margin: 0;
            font-style: italic;
        }
        .image-overlay p.date {
            font-size: 0.8rem;
            margin-top: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* DOTS */
        .dots {
            position: absolute;
            bottom: 30px;
            display: flex;
            gap: 8px;
        }
        .dots span {
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
            opacity: 0.4;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        .dots span.active, .dots span:hover {
            opacity: 1;
        }


        /* ====== KANAN / LIST ====== */
        .editorial-list {
            height: 100vh;
            background-color: #faf7f2;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.6;
        }
        .list-item:hover, .list-item.active {
            opacity: 1;
        }
        
        .list-item .number {
            font-size: 24px;
            font-weight: bold;
            color: #d1bfae;
            width: 30px;
        }
        .list-item .text h4 {
            margin: 0 0 5px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #222;
        }
        .list-item .text p {
            margin: 0;
            font-size: 0.9rem;
            color: #555;
        }
        .list-item .text small {
            display: block;
            margin-top: 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
        }

        /* NAVIGASI KANAN BAWAH */
        .list-nav {
            position: absolute;
            bottom: 40px;
            right: 40px;
            display: flex;
            gap: 15px;
        }
        .list-nav i {
            font-size: 24px;
            color: #222;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .list-nav i:hover {
            transform: scale(1.2);
        }

        /* STRIP WARNA PALING KANAN */
        .side-color-bar {
            position: absolute;
            top: 0; right: 0;
            width: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .color-green {
            background-color: #5d6d56;
            flex: 3; 
        }
        .color-brown {
            background-color: #7b432a;
            flex: 7; 
        }

        @media (max-width: 768px) {
            body, html { overflow: auto; }
            .editorial-image { height: 50vh; }
            .editorial-list { height: auto; padding: 40px 20px; }
            .side-color-bar { display: none; }
            .list-nav { position: relative; right: 0; bottom: 0; margin-top: 30px; justify-content: center; }
        }
    </style>
</head>
<body>

@include('components.user_navbar')

<section class="editorial-section container-fluid p-0">
    <div class="row g-0 h-100">

        <!-- KIRI / IMAGE -->
        <div class="col-md-6 editorial-image">
            <img id="coverImage" src="" alt="Cover">

            <div class="image-overlay text-center">
                <span class="label">{{ strtoupper($kategori) }}</span>
                <h2 id="heroTitle">Memuat...</h2>
                <p class="author" id="heroAuthor"></p>
                <p class="date" id="heroDate"></p>

                <div class="dots" id="dots">
                    <!-- Dots generated by JS -->
                </div>
            </div>
        </div>

        <!-- KANAN / LIST -->
        <div class="col-md-6 editorial-list" id="articleList">
            <!-- List Item generated by JS -->
            <div class="list-nav">
                <i class="bi bi-chevron-left" id="prevPage"></i>
                <i class="bi bi-chevron-right" id="nextPage"></i>
            </div>
        </div>

    </div>
    <div class="side-color-bar">
        <div class="color-green"></div>
        <div class="color-brown"></div>
    </div>  
</section>

<!-- TOMBOL BACK TO HOME (Optional tapi sangat membantu agar tidak tersesat) -->
<a href="{{ route('beranda') }}" style="position:fixed; top:20px; left:20px; z-index:9999; color:#fff; text-decoration:none; font-family:sans-serif; background:rgba(0,0,0,0.5); padding:8px 12px; border-radius:5px;">← Kembali</a>

<script>
// Data diambil dari Database Laravel
const rawArticles = @json($artikels);

// Transform data agar sesuai dengan skema JS asli
const articles = rawArticles.map(a => {
    return {
        title: a.judul,
        author: "GALERI JAKARTA", // a.user_id / penulis (bisa disesuaikan)
        date: new Date(a.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}),
        image: a.thumbnail ? "{{ asset('img') }}/" + a.thumbnail : "https://picsum.photos/900/1200?random=" + a.id
    };
});

// Jika data kosong, beri dummy minimal 1 agar tidak error
if (articles.length === 0) {
    articles.push({
        title: "Belum Ada Artikel",
        author: "Admin",
        date: "",
        image: "https://picsum.photos/900/1200"
    });
}
</script>

<script>
const listEl = document.getElementById("articleList");
const imageEl = document.getElementById("coverImage");
const titleEl = document.getElementById("heroTitle");
const authorEl = document.getElementById("heroAuthor");
const dateEl = document.getElementById("heroDate");
const dotsEl = document.getElementById("dots");

const perPage = 5;
let page = 0;
let activeIndex = 0;

// AMBIL NAV SEKALI
const navEl = document.querySelector(".list-nav");

// RENDER LIST
function renderList() {
    listEl.querySelectorAll(".list-item").forEach(el => el.remove());
    dotsEl.innerHTML = "";

    const start = page * perPage;
    const end = Math.min(start + perPage, articles.length);
    const currentArticles = articles.slice(start, end);

    currentArticles.forEach((article, i) => {
        const index = start + i;

        // LIST
        const item = document.createElement("div");
        item.className = "list-item";
        item.innerHTML = `
            <div class="number">${index + 1}</div>
            <div class="text">
                <h4>${article.title}</h4>
                <p>${article.date}</p>
                <small>BY: ${article.author}</small>
            </div>
        `;
        item.onclick = () => setActive(index);
        listEl.insertBefore(item, navEl);

        // DOT
        const dot = document.createElement("span");
        dot.onclick = () => setActive(index);
        dotsEl.appendChild(dot);
    });

    setActive(start);
}

// SET ACTIVE ARTICLE
function setActive(index) {
    activeIndex = index;
    const article = articles[index];
    
    imageEl.src = article.image;
    titleEl.innerText = article.title;
    authorEl.innerText = "Oleh " + article.author;
    dateEl.innerText = article.date;

    // DOT ACTIVE
    [...dotsEl.children].forEach((dot, i) => {
        dot.style.opacity = (page * perPage + i === index) ? "1" : ".4";
    });

    // LIST ACTIVE
    document.querySelectorAll(".list-item").forEach((item, i) => {
        item.classList.toggle("active", page * perPage + i === index);
    });
}

// NAVIGATION
document.getElementById("nextPage").onclick = () => {
    if ((page + 1) * perPage < articles.length) {
        page++;
        renderList();
    }
};

document.getElementById("prevPage").onclick = () => {
    if (page > 0) {
        page--;
        renderList();
    }
};

// INIT
renderList();
</script>

</body>
</html>
