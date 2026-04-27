@extends('admin.index')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Detail Pesan</h3>
        <a href="{{ route('admin.pesan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="bg-white p-4 rounded shadow-sm border">
        <div class="mb-3 border-bottom pb-3">
            <h5 class="mb-1">Dari: {{ $pesan->nama }}</h5>
            <div class="text-muted">
                <i class="fas fa-envelope me-1"></i> {{ $pesan->email }}<br>
                <i class="fas fa-clock me-1"></i> {{ \Carbon\Carbon::parse($pesan->tanggal_kirim)->format('d F Y, H:i') }}
            </div>
        </div>
        
        <div class="py-3 px-2 bg-light rounded border">
            {!! nl2br(e($pesan->isi_pesan)) !!}
        </div>

        <div class="mt-4 text-end">
            <form action="{{ route('admin.pesan.destroy', $pesan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash me-1"></i> Hapus Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection
