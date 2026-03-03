const heroRight = document.querySelector(".hero-right");

/* =========================
   RESET STATES FUNCTION
========================= */
function resetState() {
  heroRight.classList.remove("subscribe-active", "login-active", "payment-active");
}

/* =========================
   OPEN SUBSCRIBE
========================= */
document.querySelector(".btn-subscribe").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("subscribe-active");
});

/* =========================
   OPEN LOGIN
========================= */
document.querySelector(".btn-login").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("login-active");
});

/* =========================
   BACK TO TIMELINE
========================= */
document.querySelectorAll(".back-timeline").forEach(btn => {
    btn.addEventListener("click", () => {
        resetState();
    });
});

/* =========================
   PAYMENT NAVIGATION
========================= */
document.querySelector(".back-subscribe").addEventListener("click", () => {
    resetState(); // kembali ke timeline
});

/* =========================
   PASSWORD TOGGLE FUNCTION
========================= */
function passwordToggle(passId, toggleId){
  const pass = document.getElementById(passId);
  const toggle = document.getElementById(toggleId);
  toggle.addEventListener("click", () => {
    const hidden = pass.type === "password";
    pass.type = hidden ? "text" : "password";
    toggle.classList.toggle("bi-eye");
    toggle.classList.toggle("bi-eye-slash");
  });
}
passwordToggle("password","togglePassword");
passwordToggle("confirmPassword","toggleConfirmPassword");
passwordToggle("loginPassword","toggleLoginPassword");

/* =========================
   TIMELINE DOTS
========================= */
const data = [
    { year: "1978", text: "Lorem ipsum..." },
    { year: "1957", text: "Lorem ipsum..." },
    { year: "2025", text: "Lorem ipsum..." }
];
const yearEl = document.getElementById("year");
const descEl = document.getElementById("description");
const imgEl = document.getElementById("heroImage");
const dots = document.querySelectorAll(".dot");
const runner = document.getElementById("runner");
let currentIndex = 0;

function changeContent(index){
    index = Number(index);
    if(index===currentIndex) return;
    const fromDot = dots[currentIndex];
    const toDot = dots[index];
    const distance = Math.abs(toDot.offsetLeft - fromDot.offsetLeft);

    runner.style.transition = "none";
    runner.style.opacity = 1;
    runner.style.width = "0px";
    runner.style.left = fromDot.offsetLeft + "px";

    requestAnimationFrame(()=>{
        runner.style.transition = "all 0.6s ease";
        runner.style.left = toDot.offsetLeft + "px";
        runner.style.width = distance + "px";
    });

    yearEl.classList.add("fade-out");
    descEl.classList.add("fade-out");
    imgEl.classList.add("fade-out");

    setTimeout(()=>{
        yearEl.textContent = data[index].year;
        descEl.textContent = data[index].text;
        imgEl.src = `https://picsum.photos/800/1000?random=${index + 30}`;
        dots.forEach(d=>d.classList.remove("active"));
        dots[index].classList.add("active");
        yearEl.classList.remove("fade-out");
        descEl.classList.remove("fade-out");
        imgEl.classList.remove("fade-out");
        runner.style.opacity = 0;
        runner.style.width = "0px";
        currentIndex = index;
    },700);
}
dots.forEach(dot=>dot.addEventListener("click", ()=>changeContent(dot.dataset.index)));

/* =========================
   REGISTER FORM AJAX
========================= */
const registerForm = document.getElementById('registerForm');
registerForm.addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("/register", { // route Laravel register
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.errors){
            // tampilkan error validasi
            alert(Object.values(data.errors).flat().join("\n"));
            return;
        }
        // jika sukses → tampilkan payment form
        heroRight.classList.remove('subscribe-active');
        heroRight.classList.add('payment-active');
    })
    .catch(err => console.error(err));
});

/* =========================
   LOGIN FORM
========================= */
/* LOGIN AJAX FORM */
const loginForm = document.querySelector('#loginForm form');
loginForm.addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("{{ route('login') }}", {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            alert(data.error);
            return;
        }

        // Jika sukses, arahkan ke URL yang dikirim oleh Controller
        if(data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(err => console.error(err));
});