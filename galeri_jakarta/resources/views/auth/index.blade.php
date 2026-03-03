<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Slider</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="top-auth mb-3">
  <a href="#" class="btn-subscribe btn btn-primary me-2">Subscribe</a>
  <a href="#" class="btn-login btn btn-secondary">Login</a>
</div>

<div class="container-fluid hero">
    <div class="row">

        <!-- LEFT IMAGE -->
        <div class="col-md-6 hero-left">
            <img id="heroImage" src="https://picsum.photos/800/1000?random=1">
        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-md-6 hero-right">

            <div class="hero-center">

                <!-- TIMELINE CONTENT -->
                <div class="timeline-content">
                    <div id="year" class="year">1978</div>
                    <div id="description" class="description">
                        Lorem ipsum dolor sit amet consectetur...
                    </div>

                    <div class="timeline">
                        <div class="track position-relative">
                            <span class="dot active" data-index="0"></span>
                            <span class="dot" data-index="1"></span>
                            <span class="dot" data-index="2"></span>
                            <span class="runner" id="runner"></span>
                        </div>
                    </div>
                </div>

                <!-- FORMS -->
                <div id="forms-container">
                    <!-- REGISTER FORM -->
                <div id="subscribeForm" class="subscribe-form">
                    <h2>Create Account</h2>
                    <form id="registerForm" method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="text" name="name" placeholder="Nama Lengkap" class="form-control mb-2" required>
                        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
                        <div class="mb-3 position-relative">
                            <input type="password" name="password" placeholder="Password" class="form-control form-control-lg" id="password">
                            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                        </div>
                        <div class="mb-3 position-relative">
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control form-control-lg" id="confirmPassword">
                            <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                        </div>
                        <button class="btn-submit" type="submit">Lanjut Pembayaran</button>
                        <br>
                        <button type="button" class="btn-back back-timeline">← Back to timeline</button>
                    </form>
                </div>

                <!-- LOGIN FORM -->
                <div id="loginForm" class="login-form">
                    <h2>Login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" id="loginPassword" required>
                            <i class="bi bi-eye-slash toggle-password" id="toggleLoginPassword"></i>
                        </div>
                        <button type="submit" class="btn btn-submit w-100 mb-3">Login</button>
                        <button type="button" class="btn-back back-timeline">← Back to timeline</button>
                    </form>
                </div>

                <!-- PAYMENT FORM -->
                <div id="paymentForm" class="subscribe-form payment-step">
                    <h2>Payment</h2>
                    <div class="payment-card active mb-3">
                        <strong>Premium Monthly</strong>
                        <p>Rp 49.000 / Tahun</p>
                    </div>
                    <form method="POST" action="{{ route('pay') }}">
                        @csrf
                        <button id="pay-button" class="btn-submit" type="button">Bayar Sekarang</button>
                    </form>
                    <br>
                    <button type="button" class="btn-back back-subscribe">← Back</button>
                </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Custom JS -->
<script>
const heroRight = document.querySelector(".hero-right");

/* SUBSCRIBE */
document.querySelector(".btn-subscribe").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("subscribe-active");
});

/* LOGIN */
document.querySelector(".btn-login").addEventListener("click", e => {
  e.preventDefault();
  resetState();
  heroRight.classList.add("login-active");
});

/* BACK TO TIMELINE */
document.querySelectorAll(".back-timeline").forEach(btn => {
  btn.addEventListener("click", resetState);
});

/* PAYMENT BACK */
document.querySelector(".back-subscribe").addEventListener("click", () => {
  heroRight.classList.remove("payment-active");
  heroRight.classList.add("subscribe-active");
});

/* RESET STATES FUNCTION */
function resetState() {
  heroRight.classList.remove("subscribe-active", "login-active", "payment-active");
}

/* PASSWORD TOGGLE */
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

/* TIMELINE DOTS */
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

/* REGISTER AJAX FORM */
const registerForm = document.getElementById('registerForm');
registerForm.addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("{{ route('register') }}", {
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

        // Jika sukses, tampilkan payment form
        heroRight.classList.remove('subscribe-active');
        heroRight.classList.add('payment-active');
    })
    .catch(err => console.error(err));
});

/* MIDTRANS PAY BUTTON */
document.getElementById('pay-button').addEventListener('click', function () {
    fetch("{{ route('pay') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: null
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            alert(data.error);
            return;
        }
        snap.pay(data.snapToken, {
            onSuccess: function(result){ window.location = '{{ route("payment.success") }}'; },
            onPending: function(result){ alert('Menunggu pembayaran'); },
            onError: function(result){ alert('Pembayaran gagal'); }
        });
    });
});
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
</script>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

</body>
</html>