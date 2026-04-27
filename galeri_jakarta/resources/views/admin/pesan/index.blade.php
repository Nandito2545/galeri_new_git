@extends('admin.index')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Daftar Pesan Masuk</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-white">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded shadow-sm border">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Pengirim</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Tanggal Kirim</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesan as $key => $p)
                        <tr class="{{ $p->status_baca == 'belum' ? 'bg-light fw-bold' : '' }}">
                            <td class="text-center">{{ $pesan->firstItem() + $key }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                @if($p->status_baca == 'belum')
                                    <span class="badge bg-warning text-dark">Belum dibaca</span>
                                @else
                                    <span class="badge bg-success">Sudah dibaca</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_kirim)->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.pesan.show', $p->id) }}" class="btn btn-sm btn-info text-white">Baca</a>
                                <form action="{{ route('admin.pesan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pesan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pesan->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
