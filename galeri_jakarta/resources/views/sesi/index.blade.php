@extends('layouts.app') @section('content')

<div class="top-auth text-end mb-3">
    <a href="{{ route('subscribe') }}" class="btn btn-primary btn-subscribe">Subscribe</a>
    <a href="{{ route('login') }}" class="btn btn-secondary btn-login">Login</a>
</div>

<div class="container-fluid hero">
    <div class="row h-100">

        <div class="col-md-6 hero-left">
            <img id="heroImage" src="https://picsum.photos/800/1000?random=1" class="img-fluid">
        </div>

        <div class="col-md-6 hero-right">

            <div id="year" class="year">1978</div>

            <div id="description" class="description">
                Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea vero eius fugiat! Exercitationem
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi nisi fugit necessitatibus expedita doloribus mollitia. 
            </div>

            <div id="subscribeForm" class="subscribe-form step-active">

                <h2>Create Account</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="nama" placeholder="Nama Lengkap" required class="form-control mb-2">
                    <input type="email" name="email" placeholder="Email" required class="form-control mb-2">

                    <div class="mb-3 position-relative">
                        <input 
                            type="password" 
                            name="password"
                            class="form-control form-control-lg" 
                            id="password" 
                            placeholder="Password">
                        <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password">
                        <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit" id="toPayment">Lanjut Pembayaran</button>
                </form>

                <button class="btn btn-link btn-back back-timeline">← Back to timeline</button>

            </div>

            <div id="paymentForm" class="subscribe-form payment-step">

                <h2>Payment</h2>

                <div class="payment-card active">
                    <strong>Premium Monthly</strong>
                    <p>Rp 49.000 / Tahun</p>
                </div>

                <form id="payment-form" method="POST" action="{{ route('pay') }}">
                    @csrf
                    <button type="submit" id="pay-button" class="btn btn-success btn-submit w-100">Bayar Sekarang</button>
                </form>

                <button class="btn btn-link btn-back back-subscribe">← Back</button>

            </div>

            <div id="loginForm" class="login-form">
                <h2>Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <input
                        type="email"
                        name="email"
                        class="form-control form-control-lg"
                        placeholder="Email" required>
                    </div>

                    <div class="mb-3 position-relative">
                        <input
                        type="password"
                        name="password"
                        class="form-control form-control-lg"
                        id="loginPassword"
                        placeholder="Password" required>
                        <i class="bi bi-eye-slash toggle-password" id="toggleLoginPassword"></i>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit w-100 mb-3">Login</button>
                </form>

                <button class="btn btn-link btn-back back-timeline">← Back to timeline</button>
            </div>

            <div class="timeline mt-4">
                <div class="track position-relative">
                    <span class="dot active" data-index="0"></span>
                    <span class="dot" data-index="1"></span>
                    <span class="dot" data-index="2"></span>

                    <span class="runner" id="runner"></span>
                </div>
            </div>

        </div>

    </div>
</div>

<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentForm = document.getElementById('payment-form');
        
        if(paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah browser pindah halaman

                const form = this;
                const formData = new FormData(form);
                const payButton = document.getElementById('pay-button');
                
                // Ubah teks tombol jadi loading
                const originalText = payButton.innerHTML;
                payButton.innerHTML = "Memproses...";
                payButton.disabled = true;

                // Kirim request ke backend secara diam-diam (AJAX)
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Kembalikan tombol seperti semula
                    payButton.innerHTML = originalText;
                    payButton.disabled = false;

                    if (data.snapToken) {
                        // Memanggil popup Midtrans Snap
                        window.snap.pay(data.snapToken, {
                            onSuccess: function(result) {
                                alert("Pembayaran Berhasil!");
                                // Arahkan ke halaman success
                                window.location.href = "{{ route('payment.success') }}"; 
                            },
                            onPending: function(result) {
                                alert("Menunggu pembayaran Anda!");
                            },
                            onError: function(result) {
                                alert("Pembayaran Gagal!");
                            },
                            onClose: function() {
                                alert("Anda menutup popup sebelum menyelesaikan pembayaran.");
                            }
                        });
                    } else {
                        alert("Gagal mendapatkan token pembayaran dari server.");
                    }
                })
                .catch(error => {
                    payButton.innerHTML = originalText;
                    payButton.disabled = false;
                    console.error("Error:", error);
                    alert("Terjadi kesalahan jaringan.");
                });
            });
        }
    });
</script>

@endsection