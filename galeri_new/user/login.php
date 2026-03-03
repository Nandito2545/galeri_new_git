<?php
session_start();
include 'admin/koneksi.php';

/* =========================
   MIDTRANS CONFIG (SANDBOX)
========================= */
require_once 'midtrans/Midtrans.php'; // pastikan folder midtrans ada

\Midtrans\Config::$serverKey = 'SB-Mid-server-XXXXXXXXXXXX'; // ganti dengan server key sandbox kamu
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

/* =========================
   REGISTER PROCESS
========================= */
if (isset($_POST['register'])) {

    $nama     = mysqli_real_escape_string($k, $_POST['nama']);
    $email    = mysqli_real_escape_string($k, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($k, "INSERT INTO users (nama,email,password) 
                      VALUES ('$nama','$email','$password')");

    $user_id = mysqli_insert_id($k);

    // Simpan user sementara ke session untuk pembayaran
    $_SESSION['pending_user'] = $user_id;

    header("Location: login?step=payment");
    exit;
}

/* =========================
   LOGIN PROCESS
========================= */
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($k, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($k, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];

            header("Location: index");
            exit;
        }
    }

    $error = "Email atau password salah";
}

/* =========================
   PAYMENT PROCESS
========================= */
if (isset($_POST['pay'])) {

    $user_id = $_SESSION['pending_user'];

    $order_id = "ORDER-" . time();

    $params = array(
        'transaction_details' => array(
            'order_id' => $order_id,
            'gross_amount' => 49000,
        ),
        'customer_details' => array(
            'first_name' => "User",
            'email' => "test@mail.com",
        ),
    );

    $snapToken = \Midtrans\Snap::getSnapToken($params);

    echo "
    <script src='https://app.sandbox.midtrans.com/snap/snap.js' 
            data-client-key='SB-Mid-client-XXXXXXXX'></script>
    <script>
        snap.pay('$snapToken', {
            onSuccess: function(result){
                window.location='payment_success.php?order_id=$order_id';
            },
            onPending: function(result){
                alert('Menunggu pembayaran');
            },
            onError: function(result){
                alert('Pembayaran gagal');
            }
        });
    </script>
    ";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Slider</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="user/assets/css/style.css">
</head>
<body>
<div class="top-auth">
  <a href="#" class="btn-subscribe">Subscribe</a>
  <a href="#" class="btn-login">Login</a>
</div>

<div class="container-fluid hero">
    <div class="row h-100">

        <!-- LEFT IMAGE -->
        <div class="col-md-6 hero-left">
            <img id="heroImage" src="https://picsum.photos/800/1000?random=1">
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

            <input type="text" placeholder="Nama Lengkap" required>
            <input type="email" placeholder="Email" required>
            <div class="mb-3 position-relative">
            <input 
                type="password" 
                class="form-control form-control-lg" 
                id="password" 
                placeholder="Password">

            <i 
                class="bi bi-eye-slash toggle-password"
                id="togglePassword">
            </i>
            </div>
            <div class="mb-3 position-relative">
            <input type="password" id="confirmPassword">
            <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword"></i>
            </div>

            <button class="btn-submit" id="toPayment">Lanjut Pembayaran</button>
            <br>
            <button class="btn-back back-timeline">← Back to timeline</button>

            </div>

            <div id="paymentForm" class="subscribe-form payment-step">

            <h2>Payment</h2>

            <div class="payment-card active">
                <strong>Premium Monthly</strong>
                <p>Rp 49.000 / Tahun</p>
            </div>

            <button class="btn-submit">Bayar Sekarang</button>
            <br>
            <button class="btn-back back-subscribe">← Back</button>

            </div>
            <div id="loginForm" class="login-form">
                <h2>Login</h2>

                <div class="mb-3">
                    <input
                    type="email"
                    class="form-control form-control-lg"
                    placeholder="Email">
                </div>

                <div class="mb-3 position-relative">
                    <input
                    type="password"
                    class="form-control form-control-lg"
                    id="loginPassword"
                    placeholder="Password">

                    <i
                    class="bi bi-eye-slash toggle-password"
                    id="toggleLoginPassword">
                    </i>
                </div>

                <button class="btn btn-submit w-100 mb-3">Login</button>

                <button class="btn-back back-timeline">← Back to timeline</button>
            </div>





            <!-- TIMELINE -->
            <div class="timeline">
                <div class="track">
                    <span class="dot active" data-index="0"></span>
                    <span class="dot" data-index="1"></span>
                    <span class="dot" data-index="2"></span>

                    <span class="runner" id="runner"></span>
                </div>
            </div>
            


        </div>

    </div>
</div>
</body>
</html>
