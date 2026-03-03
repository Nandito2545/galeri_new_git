<style>
  /* ================= SECTION ================= */
.editorial-photo {
  position: relative;
  height: 100vh;
  display: flex;
  overflow: hidden;
  font-family: "Playfair Display", serif;
}

/* ================= LEFT ================= */
.photo-left {
  width: 65%;
  margin-top: 4%;
  padding: 0px 20px;
}

.photo-hero {
  position: relative;
  height: 100%;
}

.photo-hero img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* LABEL */
.photo-label {
  position: absolute;
  top: 0;
  left: 0;
  background: #7f9484;
  color: #fff;
  padding: 10px 18px;
  font-size: 14px;
  letter-spacing: 1px;
}

/* TITLE */
.photo-title {
  position: absolute;
  top: 80px;
  left: 30px;
  font-size: 40px;
  line-height: 1.15;
}

.photo-title span {
  display: inline-block;
  background: #7f9484;
  color: #fff;
  padding: 10px 18px;
  margin-bottom: 6px;
}

/* DOTS */
.photo-dots {
  position: absolute;
  bottom: 40px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

.photo-dots span {
  width: 10px;
  height: 10px;
  background: #b46a45;
  border-radius: 50%;
}

/* ================= RIGHT ================= */
.photo-right {
  width: 38%;
  margin-top: 4%;
  padding: 0px 35px;
}

.photo-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 28px 22px;
}

.photo-card {
  padding-right: 14px;
  border-right: 1px solid #222;
}

.photo-card img {
  width: 100%;
  height: 130px;
  object-fit: cover;
  margin-bottom: 8px;
}

.photo-card p {
  font-size: 15px;
  margin-bottom: 4px;
}

.photo-card small {
  color: #b24;
  font-size: 12px;
}

/* NAV */
.photo-nav {
  margin-top: 30px;
  display: flex;
  justify-content: center;
  gap: 18px;
}

.photo-nav i {
  width: 44px;
  height: 44px;
  border: 2px solid #c62828;
  border-radius: 50%;
  color: #c62828;
  font-size: 22px;

  display: flex;
  align-items: center;
  justify-content: center;

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
<section class="editorial-photo">

  <!-- GLOBAL MENU (PAKAI YANG SUDAH ADA) -->
  <div class="global-menu">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <!-- LEFT -->
  <div class="photo-left">
    <div class="photo-hero">

      <div class="photo-label">PHOTO</div>

      <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e">

      <div class="photo-title">
        <span>Lorem ipsum, or lipsum</span><br>
        <span>as it is sometimes</span><br>
        <span>known</span>
      </div>

      <div class="photo-dots">
        <span></span><span></span><span></span><span></span><span></span>
      </div>

    </div>
  </div>

  <!-- RIGHT -->
  <aside class="photo-right">

    <div class="photo-grid">
      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>

      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>

      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>

      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>

      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>

      <div class="photo-card">
        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2">
        <p>Lorem ipsum dolor sit amet</p>
        <small>BY: ANNA KARINA</small>
      </div>
    </div>

    <div class="photo-nav">
      <i class="bi bi-arrow-left"></i>
      <i class="bi bi-arrow-right"></i>
    </div>

  </aside>

  <!-- STRIP WARNA -->
  <div class="color-strip">
    <div class="brown"></div>
    <div class="green"></div>
  </div>

</section>
