@extends('layouts.app') <!-- layout utama -->

@section('content')

<div class="top-auth text-end mb-3">
    <a href="{{ route('subscribe') }}" class="btn btn-primary btn-subscribe">Subscribe</a>
    <a href="{{ route('login') }}" class="btn btn-secondary btn-login">Login</a>
</div>

<div class="container-fluid hero">
    <div class="row h-100">

        <!-- LEFT IMAGE -->
        <div class="col-md-6 hero-left">
            <img id="heroImage" src="https://picsum.photos/800/1000?random=1" class="img-fluid">
        </div>

        <!-- RIGHT CONTENT -->
        <div class="col-md-6 hero-right">

            <div id="year" class="year">1978</div>

            <div id="description" class="description">
                Lorem ipsum dolor sit amet consectetur adipiscing elit. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ea vero eius fugiat! Exercitationem
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Officiis, a accusamus blanditiis nostrum pariatur tenetur error in fugiat eos ut dolorum repellat eius excepturi nisi fugit necessitatibus expedita doloribus mollitia. 
            </div>

            <!-- SUBSCRIBE FORM (HIDDEN DEFAULT) -->
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

            <!-- PAYMENT FORM -->
            <div id="paymentForm" class="subscribe-form payment-step">

                <h2>Payment</h2>

                <div class="payment-card active">
                    <strong>Premium Monthly</strong>
                    <p>Rp 49.000 / Tahun</p>
                </div>

                <form method="POST" action="{{ route('pay') }}">
                    @csrf
                    <button class="btn btn-success btn-submit w-100">Bayar Sekarang</button>
                </form>

                <button class="btn btn-link btn-back back-subscribe">← Back</button>

            </div>

            <!-- LOGIN FORM -->
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

            <!-- TIMELINE -->
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

@endsection