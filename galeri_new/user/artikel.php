<section class="book-reader">

    <!-- KIRI / COVER -->
    <div class="book-cover">
        <img 
            src="https://picsum.photos/900/1200?grayscale" 
            alt="Cover"
            class="cover-image"
        >

        <div class="cover-overlay"></div>

        <div class="cover-text">
            <span class="label">KOLOM</span>
            <h2>Kisah Cinta Aristoteles</h2>
            <p class="author">Oleh Alexander Karamazov</p>
            <p class="date">30 Januari 2026 · 22:30 WIB</p>

            <div class="cover-share">
                <i class="bi bi-box-arrow-up"></i>
            </div>
        </div>
    </div>

    <!-- KANAN / CONTENT -->
    <div class="book-content">

        <!-- ICON SOSIAL -->
        <div class="social-vertical">
            <i class="bi bi-airplane"></i>
            <i class="bi bi-instagram"></i>
            <i class="bi bi-whatsapp"></i>
            <i class="bi bi-facebook"></i>
        </div>

        <!-- CONTAINER HALAMAN -->
        <div class="pages" id="pages"></div>

        <!-- TEKS SUMBER (HANYA 1 P, DARI DATABASE) -->
        <p id="sourceText" class="source-text">
            <strong>Lorem Ipsum</strong> is simply dummy text of the printing and
            typesetting industry. Lorem Ipsum has been the industry’s standard dummy
            text ever since the 1500s. Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            <br>
            <br>
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source
            It has survived not only five centuries, but also the leap into
            electronic typesetting, remaining essentially unchanged.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            <br>
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Lorem ipsum was popularised in the 1960s with the release of
            Letraset sheets containing Lorem Ipsum passages. discovered the undoubtable source.
            Richard McClintock, a Latin profess
            <br>
    
            Contrary to popular belief, Lorem Ipsum is not simply random text.
            It has roots in a piece of classical Latin literature from 45 BC,
            making it over 2000 years old
            <br>
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            <br>
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.
            Richard McClintock, a Latin professor at Hampden-Sydney College,
            discovered the undoubtable source.

            <b>Lorem Ipsum</b> was popularised in the 1960s with the release of
            Letraset sheets containing Lorem Ipsum passages.

            Contrary to popular belief, Lorem Ipsum is not simply random text.
            It has roots in a piece of classical Latin literature from 45 BC,
            making it over 2000 years old.
            
        </p>

        <!-- NAVIGASI -->
        <div class="page-nav">
            <button id="prev"><i class="bi bi-chevron-left"></i></button>
            <button id="next"><i class="bi bi-chevron-right"></i></button>
        </div>

    </div>
    <!-- STRIP WARNA KANAN -->
    <div class="side-color-bar">
        <div class="color-green"></div>
        <div class="color-brown"></div>
    </div>

</section>
<script>
const source = document.getElementById("sourceText");
const container = document.getElementById("pages");
const pageHeight = container.clientHeight;

let pages = [];
let current = 0;

/* ELEMENT UJI TINGGI */
const temp = document.createElement("div");
temp.style.position = "absolute";
temp.style.visibility = "hidden";
temp.style.width = container.clientWidth + "px";
temp.style.lineHeight = "1.9";
temp.style.fontSize = "18px";
document.body.appendChild(temp);

/* BANGUN FRAGMENT DENGAN BENAR */
function buildFragments(node) {
    // TEXT → PECAH PER KATA
    if (node.nodeType === Node.TEXT_NODE) {
        return node.textContent
            .split(/(\s+)/)
            .map(t => document.createTextNode(t));
    }

    // BR → LANGSUNG RETURN
    if (node.nodeName === "BR") {
        return [document.createElement("br")];
    }

    // TAG (b, strong, dll)
    const wrapper = document.createElement(node.nodeName);
    Array.from(node.attributes).forEach(attr => {
        wrapper.setAttribute(attr.name, attr.value);
    });

    const children = Array.from(node.childNodes)
        .flatMap(buildFragments);

    return children.map(child => {
        const el = wrapper.cloneNode(false);
        el.appendChild(child);
        return el;
    });
}

/* AMBIL SEMUA FRAGMENT */
const fragments = Array.from(source.childNodes)
    .flatMap(buildFragments);

/* BUAT HALAMAN */
let page = document.createElement("div");
page.className = "page";

let p = document.createElement("p");
page.appendChild(p);

fragments.forEach(fragment => {
    p.appendChild(fragment);

    temp.innerHTML = "";
    temp.appendChild(p.cloneNode(true));

    if (temp.scrollHeight > pageHeight) {
        p.removeChild(fragment);
        pages.push(page);

        page = document.createElement("div");
        page.className = "page";

        p = document.createElement("p");
        p.appendChild(fragment);
        page.appendChild(p);
    }
});

pages.push(page);
document.body.removeChild(temp);

/* RENDER */
pages.forEach((pg, i) => {
    if (i === 0) pg.classList.add("active", "first");
    container.appendChild(pg);
});

/* NAVIGASI */
document.getElementById("next").onclick = () => {
    if (current < pages.length - 1) {
        pages[current].classList.remove("active");
        current++;
        pages[current].classList.add("active");
    }
};

document.getElementById("prev").onclick = () => {
    if (current > 0) {
        pages[current].classList.remove("active");
        current--;
        pages[current].classList.add("active");
    }
};
</script>
