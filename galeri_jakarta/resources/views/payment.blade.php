@extends('layouts.app')

@section('content')
<section class="payment-section">
    <div class="container">
        <h2>Upgrade ke Premium</h2>
        <div class="payment-card active">
            <strong>Premium Monthly</strong>
            <p>Rp 49.000 / Tahun</p>
        </div>
        <form method="POST" action="{{ route('pay') }}">
            @csrf
            <button id="pay-button" class="btn btn-submit" type="button">Bayar Sekarang</button>
        </form>
    </div>
</section>
@endsection