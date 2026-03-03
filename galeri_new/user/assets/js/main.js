const data = [
    { year: "1978", text: "Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea vero eius fugiat! Exercitationem Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi nisi fugit necessitatibus expedita doloribus mollitia." },
    { year: "1957", text: "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi nisi fugit necessitatibus expedita doloribus mollitia. consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi" },
    { year: "2025", text: "Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea vero eius fugiat! Exercitationem Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi nisi fugit necessitatibus expedita doloribus mollitia." }
];

const yearEl = document.getElementById("year");
const descEl = document.getElementById("description");
const imgEl = document.getElementById("heroImage");
const dots = document.querySelectorAll(".dot");
const runner = document.getElementById("runner");

let currentIndex = 0;

function changeContent(index) {
    index = Number(index);
    if (index === currentIndex) return;

    const fromDot = dots[currentIndex];
    const toDot = dots[index];

    const fromX = fromDot.offsetLeft;
    const toX = toDot.offsetLeft;
    const distance = Math.abs(toX - fromX);

    /* === RESET RUNNER === */
    runner.style.transition = "none";
    runner.style.opacity = 1;
    runner.style.width = "0px";

    // 🔥 FIX UTAMA: START DARI DOT ASAL
    runner.style.left = fromX + "px";

    requestAnimationFrame(() => {
        runner.style.transition = "all 0.6s ease";

        // kalau geser ke kiri, geser posisi start
        if (toX < fromX) {
            runner.style.left = toX + "px";
        }

        runner.style.width = distance + "px";
    });

    /* === FADE CONTENT === */
    yearEl.classList.add("fade-out");
    descEl.classList.add("fade-out");
    imgEl.classList.add("fade-out");

    setTimeout(() => {
        yearEl.textContent = data[index].year;
        descEl.textContent = data[index].text;
        imgEl.src = `https://picsum.photos/800/1000?random=${index + 30}`;

        dots.forEach(d => d.classList.remove("active"));
        dots[index].classList.add("active");

        yearEl.classList.remove("fade-out");
        descEl.classList.remove("fade-out");
        imgEl.classList.remove("fade-out");

        runner.style.opacity = 0;
        runner.style.width = "0px";

        currentIndex = index;
    }, 700);
}

dots.forEach(dot => {
    dot.addEventListener("click", () => {
        changeContent(dot.dataset.index);
    });
});


const heroRight = document.querySelector(".hero-right");

/* OPEN SUBSCRIBE */
document.querySelector(".btn-subscribe").addEventListener("click", e => {
  e.preventDefault();
  heroRight.classList.add("subscribe-active");
});

/* BACK TO TIMELINE */
document.querySelector(".back-timeline").addEventListener("click", () => {
  heroRight.classList.remove("subscribe-active");
  heroRight.classList.remove("payment-active");
});

/* TO PAYMENT */
document.getElementById("toPayment").addEventListener("click", () => {
  heroRight.classList.add("payment-active");
});

/* BACK TO SUBSCRIBE */
document.querySelector(".back-subscribe").addEventListener("click", () => {
  heroRight.classList.remove("payment-active");
});

/* PAYMENT SELECT */
document.querySelectorAll(".payment-card").forEach(card => {
  card.addEventListener("click", () => {
    document.querySelectorAll(".payment-card").forEach(c => c.classList.remove("active"));
    card.classList.add("active");
  });
});

const password = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

togglePassword.addEventListener("click", () => {
  const isHidden = password.type === "password";
  password.type = isHidden ? "text" : "password";

  togglePassword.classList.toggle("bi-eye");
  togglePassword.classList.toggle("bi-eye-slash");
});

const confirmPassword = document.getElementById("confirmPassword");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

toggleConfirmPassword.addEventListener("click", () => {
  const isHidden = confirmPassword.type === "password";
  confirmPassword.type = isHidden ? "text" : "password";

  toggleConfirmPassword.classList.toggle("bi-eye");
  toggleConfirmPassword.classList.toggle("bi-eye-slash");
});

/* ===============================
   LOGIN BUTTON ACTION
================================ */

const loginBtn = document.querySelector(".btn-login");
const loginForm = document.getElementById("loginForm");

loginBtn.addEventListener("click", e => {
  e.preventDefault();
  heroRight.classList.add("login-active");
  heroRight.classList.remove("subscribe-active", "payment-active");
});

/* ===============================
   LOGIN BACK TO TIMELINE
================================ */

document.querySelectorAll(".back-timeline").forEach(btn => {
  btn.addEventListener("click", () => {
    heroRight.classList.remove("login-active");
  });
});

/* ===============================
   LOGIN PASSWORD TOGGLE
================================ */

const loginPassword = document.getElementById("loginPassword");
const toggleLoginPassword = document.getElementById("toggleLoginPassword");

toggleLoginPassword.addEventListener("click", () => {
  const hidden = loginPassword.type === "password";
  loginPassword.type = hidden ? "text" : "password";

  toggleLoginPassword.classList.toggle("bi-eye");
  toggleLoginPassword.classList.toggle("bi-eye-slash");
});


/* RESET ALL STATES */
function resetState() {
  heroRight.classList.remove(
    "subscribe-active",
    "payment-active",
    "login-active"
  );
}

/* ===============================
   OPEN SUBSCRIBE
================================ */
document.querySelector(".btn-subscribe").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("subscribe-active");
});

/* ===============================
   OPEN LOGIN
================================ */
document.querySelector(".btn-login").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("login-active");
});

/* ===============================
   BACK TO TIMELINE
================================ */
document.querySelectorAll(".back-timeline").forEach(btn => {
  btn.addEventListener("click", () => {
    resetState();
  });
});

/* ===============================
   TO PAYMENT
================================ */
document.getElementById("toPayment").addEventListener("click", () => {
  heroRight.classList.add("payment-active");
});

/* ===============================
   BACK TO SUBSCRIBE
================================ */
document.querySelector(".back-subscribe").addEventListener("click", () => {
  heroRight.classList.remove("payment-active");
});

/* ===============================
   PAYMENT SELECT
================================ */
document.querySelectorAll(".payment-card").forEach(card => {
  card.addEventListener("click", () => {
    document
      .querySelectorAll(".payment-card")
      .forEach(c => c.classList.remove("active"));
    card.classList.add("active");
  });
});
