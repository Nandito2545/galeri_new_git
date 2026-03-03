<section class="editorial-section container-fluid p-0">
    <div class="row g-0 h-100">

        <!-- KIRI / IMAGE -->
        <div class="col-md-6 editorial-image">
            <img id="coverImage" src="https://picsum.photos/900/1200" alt="Cover">

            <div class="image-overlay text-center">
                <span class="label">KOTA</span>
                <h2>Kisah Cinta Aristoteles</h2>
                <p class="author">Oleh Alexander Karamazov</p>
                <p class="date">30 Januari 2026 · 22:30 WIB</p>

                <div class="dots" id="dots">
                    <span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <!-- KANAN / LIST -->
        <div class="col-md-6 editorial-list" id="articleList">

            <div class="list-item">
                <div class="number">1</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">2</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">3</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">4</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">5</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>
            
            <div class="list-item">
                <div class="number">1</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">2</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">3</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">4</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

            <div class="list-item">
                <div class="number">5</div>
                <div class="text">
                    <h4>The Comfiest Platform Sneakers</h4>
                    <p>Our Editors Own</p>
                    <small>BY: MADISON FELLER</small>
                </div>
            </div>

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
<script>
const articles = [
    { title: "The Comfiest Platform Sneakers", author: "MADISON FELLER", image: "https://picsum.photos/id/1011/900/1200" },
    { title: "Minimalist Fashion That Lasts", author: "MADISON FELLER", image: "https://picsum.photos/id/1012/900/1200" },
    { title: "Why Neutral Colors Work", author: "MADISON FELLER", image: "https://picsum.photos/id/1013/900/1200" },
    { title: "Design That Feels Timeless", author: "MADISON FELLER", image: "https://picsum.photos/id/1014/900/1200" },
    { title: "Comfort Meets Style", author: "MADISON FELLER", image: "https://picsum.photos/id/1015/900/1200" },

    { title: "Urban Aesthetic 2026", author: "MADISON FELLER", image: "https://picsum.photos/id/1016/900/1200" },
    { title: "Fashion Without Effort", author: "MADISON FELLER", image: "https://picsum.photos/id/1018/900/1200" },
    { title: "Soft Silhouettes Trend", author: "MADISON FELLER", image: "https://picsum.photos/id/1020/900/1200" },
    { title: "Editorial Styling Secrets", author: "MADISON FELLER", image: "https://picsum.photos/id/1021/900/1200" },
    { title: "The Art of Simplicity", author: "MADISON FELLER", image: "https://picsum.photos/id/1022/900/1200" }
];
</script>
<script>
const listEl = document.getElementById("articleList");
const imageEl = document.getElementById("coverImage");
const dotsEl = document.getElementById("dots");

const perPage = 5;
let page = 0;
let activeIndex = 0;

// AMBIL NAV SEKALI (BIAR NGGAK KEHAPUS)
const navEl = document.querySelector(".list-nav");

// RENDER LIST
function renderList() {
    // hapus list-item saja
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
                <p>Our Editors Own</p>
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

    // DOT ACTIVE
    [...dotsEl.children].forEach((dot, i) => {
        dot.style.opacity =
            (page * perPage + i === index) ? "1" : ".4";
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
