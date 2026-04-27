<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Galeri Jakarta</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6f8; font-family: 'Instrument Sans', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .verify-card { max-width: 500px; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; text-align: center; }
        .icon-box { width: 80px; height: 80px; background: #e8f5e9; color: #4caf50; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 35px; margin: 0 auto 20px; }
        h4 { font-weight: bold; color: #333; margin-bottom: 15px; }
        p { color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 25px; }
        .btn-custom { background: #212529; color: #fff; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; width: 100%; transition: all 0.3s ease; }
        .btn-custom:hover { background: #343a40; }
        .logout-link { display: inline-block; margin-top: 20px; color: #dc3545; text-decoration: none; font-size: 14px; font-weight: 500; }
        .logout-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="verify-card">
    <div class="icon-box">
        <i class="bi bi-envelope-check-fill"></i>
    </div>
    <h4>Verifikasi Email Anda</h4>
    <p>
        Terima kasih telah mendaftar! Sebelum mulai menggunakan fitur premium, harap pastikan email Anda aktif dengan menekan tombol verifikasi pada email yang baru saja kami kirimkan ke <strong>{{ auth()->user()->email }}</strong>.
    </p>

    @if (session('success'))
        <div class="alert alert-success" style="font-size: 14px; text-align: left;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-custom">Kirim Ulang Email Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none;">@csrf</form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="logout-link">
        <i class="bi bi-box-arrow-left"></i> Logout / Gunakan Akun Lain
    </a>
</div>

</body>
</html>
