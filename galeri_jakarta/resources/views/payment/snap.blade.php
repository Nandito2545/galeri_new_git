<h2>Pembayaran (Sandbox)</h2>
<button id="pay-button">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            alert('Pembayaran sukses!');
            window.location = '{{ route("payment.success") }}';
        },
        onPending: function(result){
            alert('Menunggu pembayaran (Pending)');
        },
        onError: function(result){
            alert('Pembayaran gagal');
        }
    });
});
</script>