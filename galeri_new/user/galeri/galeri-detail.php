<style>
  /* ================= SECTION ================= */
.editorial-photo-detail {
  position: relative;
  height: 100vh;
  display: flex;
  overflow: hidden;
  font-family: "Playfair Display", serif;
  background: #fff;
}

/* ================= LEFT ================= */
.detail-left {
  width: 55%;
}

.detail-image {
  position: relative;
  height: 100%;
}

.detail-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: grayscale(100%);
}

/* LABEL */
.detail-label {
  position: absolute;
  bottom: 90px;
  left: 50%;
  transform: translateX(-50%);
  background: #7f9484;
  color: #fff;
  padding: 10px 18px;
  font-size: 14px;
  letter-spacing: 1px;
  white-space: nowrap;
}


/* DOTS */
.detail-dots {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

.detail-dots span {
  width: 10px;
  height: 10px;
  background: #b46a45;
  border-radius: 50%;
}

/* ================= RIGHT ================= */
.detail-right {
  width: 43%;
  padding: 80px 60px;
}

.detail-right h1 {
  font-size: 40px;
  line-height: 1.25;
  color: #8b4a2f;
  margin-bottom: 20px;
}

.detail-right hr {
  border: none;
  border-top: 1px solid #222;
  margin: 18px 0;
}

.detail-meta {
  font-size: 16px;
}

.detail-desc {
  font-size: 22px;
  line-height: 1.6;
}

/* SOURCE */
.detail-source {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 15px;
}

.detail-source i:first-child {
  width: 70px;
  height: 50px;
  background: #c62828;
  color: #fff;
  border-radius: 100%;

  display: flex;
  align-items: center;
  justify-content: center;
}

.detail-source i:last-child {
  margin-left: auto;
  font-size: 20px;
  cursor: pointer;
}

/* ================= STRIP ================= */
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

</style>
<section class="editorial-photo-detail">

  <!-- GLOBAL MENU (PAKAI CSS GLOBAL YANG SUDAH ADA) -->
  <div class="global-menu">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <!-- LEFT IMAGE -->
  <div class="detail-left">
    <div class="detail-image">

      <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c">

      <div class="detail-label">PHOTO OF THE WEEKS</div>

      <div class="detail-dots">
        <span></span><span></span><span></span><span></span><span></span>
      </div>

    </div>
  </div>

  <!-- RIGHT CONTENT -->
  <aside class="detail-right">

    <h1>
      Ini Judulnya De Finibus<br>
      Bonorum et Malorum for<br>
      use in a type specimen book.
    </h1>

    <hr>

    <div class="detail-meta">
      By: Galeri Buku Jakarta | Senin, 02/02/2026
    </div>

    <hr>

    <p class="detail-desc">
      Ini keterangan Foto... Lorem ipsum, or lipsum as it is sometimes known, is dummy
      text used in laying out print, graphic or web designs. The passage is attributed
      to an unknown typesetter in the 15th century who is thought to have scrambled
      parts of Cicero’s De Finibus Bonorum et Malorum for use in a type specimen book.
    </p>

    <hr>

    <div class="detail-source">
      <i class="bi bi-camera"></i>
      <span>
        ini keterangan sumber foto: Finibus Bonorum et Malorum for use in a type specimen book.
      </span>
      <i class="bi bi-box-arrow-up-right"></i>
    </div>

  </aside>

  <!-- STRIP -->
  <div class="color-strip">
    <div class="brown"></div>
    <div class="green"></div>
  </div>

</section>
