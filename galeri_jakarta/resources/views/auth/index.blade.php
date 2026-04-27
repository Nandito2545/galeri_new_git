<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Slider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="col-md-6 hero-left">
            <img id="heroImage" src="https://picsum.photos/800/1000?random=1">
        </div>
        <div class="col-md-6 hero-right">
            <div class="hero-center">
                <div class="timeline-content">
                    <div id="year" class="year">1978</div>
                    <div id="description" class="description">Lorem ipsum dolor sit amet...</div>
                    <div class="timeline">
                        <div class="track position-relative">
                            <span class="dot active" data-index="0"></span>
                            <span class="dot" data-index="1"></span>
                            <span class="dot" data-index="2"></span>
                            <span class="runner" id="runner"></span>
                        </div>
                    </div>
                </div>

                <div id="forms-container">
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
                        <button class="btn-submit w-100" type="submit">Lanjut Pembayaran</button>
                        <button type="button" class="btn-back back-timeline w-100 mt-2">← Back to timeline</button>
                    </form>
                </div>

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
                        <button type="button" class="btn-back back-timeline w-100">← Back to timeline</button>
                    </form>
                </div>

                <div id="paymentForm" class="subscribe-form payment-step">
                    <h2>Payment</h2>
                    <div class="payment-card active mb-3">
                        <strong>Premium Monthly</strong>
                        <p>Rp 49.000 / Tahun</p>
                    </div>
                    <form id="payForm">
                        @csrf
                        <button id="pay-button" class="btn-submit w-100" type="button">Bayar Sekarang</button>
                    </form>
                    <button type="button" class="btn-back back-subscribe w-100 mt-2">← Back</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
const heroRight = document.querySelector(".hero-right");

document.querySelector(".btn-subscribe").addEventListener("click", e => { e.preventDefault(); resetState(); heroRight.classList.add("subscribe-active"); });
document.querySelector(".btn-login").addEventListener("click", e => { e.preventDefault(); resetState(); heroRight.classList.add("login-active"); });
document.querySelectorAll(".back-timeline").forEach(btn => { btn.addEventListener("click", resetState); });
document.querySelector(".back-subscribe").addEventListener("click", () => { heroRight.classList.remove("payment-active"); heroRight.classList.add("subscribe-active"); });

function resetState() { heroRight.classList.remove("subscribe-active", "login-active", "payment-active"); }

function passwordToggle(passId, toggleId){
  const pass = document.getElementById(passId);
  const toggle = document.getElementById(toggleId);
  if(toggle && pass) {
    toggle.addEventListener("click", () => {
      const hidden = pass.type === "password";
      pass.type = hidden ? "text" : "password";
      toggle.classList.toggle("bi-eye");
      toggle.classList.toggle("bi-eye-slash");
    });
  }
}
passwordToggle("password","togglePassword");
passwordToggle("confirmPassword","toggleConfirmPassword");
passwordToggle("loginPassword","toggleLoginPassword");

/* REGISTER AJAX FORM */
const registerForm = document.getElementById('registerForm');
registerForm.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Memproses...'; submitBtn.disabled = true;

    fetch("{{ route('register') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        submitBtn.innerHTML = originalBtnText; submitBtn.disabled = false;
        if(data.error){ alert(data.error); return; }
        if(data.redirect) { window.location.href = data.redirect; }
    })
    .catch(err => {
        submitBtn.innerHTML = originalBtnText; submitBtn.disabled = false;
        console.error(err); alert("Gagal mendaftar. Cek koneksi Anda.");
    });
});

/* LOGIN AJAX FORM */
const loginForm = document.querySelector('#loginForm form');
if(loginForm) {
    loginForm.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = 'Memproses...'; submitBtn.disabled = true;

        fetch("{{ route('login') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        }).then(res => res.json()).then(data => {
            if(data.error){ alert(data.error); submitBtn.innerHTML = 'Login'; submitBtn.disabled = false; return; }
            if(data.redirect) { window.location.href = data.redirect; }
        });
    });
}

/* MIDTRANS PAY BUTTON */
document.getElementById('pay-button').addEventListener('click', function () {
    const payBtn = this;
    const originalBtnText = payBtn.innerHTML;
    
    payBtn.innerHTML = 'Memuat...';
    payBtn.disabled = true;

    fetch("{{ route('pay') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async res => {
        const data = await res.json();
        // Jika server mengembalikan status error (400 / 500)
        if (!res.ok) { throw new Error(data.message || "Gagal menghubungi server"); }
        return data;
    })
    .then(data => {
        payBtn.innerHTML = originalBtnText;
        payBtn.disabled = false;

        if (data.snapToken) {
            window.snap.pay(data.snapToken, {
                onSuccess: function(result){ window.location.href = '{{ route("payment.success") }}'; },
                onPending: function(result){ alert('Menunggu pembayaran diselesaikan.'); window.location.reload(); },
                onError: function(result){ alert('Pembayaran gagal!'); window.location.reload(); },
                onClose: function(){ alert('Anda menutup popup sebelum menyelesaikan pembayaran.'); }
            });
        } else {
            alert("Gagal memuat token Midtrans.");
        }
    })
    .catch(err => {
        payBtn.innerHTML = originalBtnText;
        payBtn.disabled = false;
        console.error("Payment Error:", err);
        // Alert ini akan memberitahukan ALASAN PERSIS kenapa gagal
        alert("Error Sistem: " + err.message); 
    });
});
</script>

</body>
</html>