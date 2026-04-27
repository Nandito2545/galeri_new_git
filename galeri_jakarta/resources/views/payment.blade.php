<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Premium - Galeri Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6f8; font-family: 'Instrument Sans', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .payment-card-container { max-width: 400px; width: 100%; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; text-align: center; }
        h2 { font-weight: bold; margin-bottom: 20px; }
        .payment-card { border: 2px solid #222; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; cursor: pointer; }
        .payment-card strong { display: block; font-size: 1.2rem; margin-bottom: 5px; }
        .payment-card p { margin: 0; color: #555; }
        .btn-submit { background: #212529; color: #fff; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; width: 100%; transition: all 0.3s ease; }
        .btn-submit:hover { background: #343a40; }
        .logout-link { display: inline-block; margin-top: 20px; color: #dc3545; text-decoration: none; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>

<div class="payment-card-container">
    <h2>Upgrade ke Premium</h2>
    <div class="payment-card">
        <strong>Premium Monthly</strong>
        <p>Rp 49.000 / Tahun</p>
    </div>
    
    <button id="pay-button" class="btn-submit" type="button">Bayar Sekarang</button>
    
    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display:none;">@csrf</form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="logout-link">Batal & Logout</a>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
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
        alert("Error Sistem: " + err.message); 
    });
});
</script>

</body>
</html>