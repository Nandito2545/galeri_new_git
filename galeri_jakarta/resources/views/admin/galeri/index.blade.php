@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .container-fluid { background: #ffffff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 8px 24px rgba(0,0,0,.08) !important; }
    .btn-dark { background: #212529 !important; color:#fff !important; }
</style>

<div class="container-fluid py-4">
    <h3 class="fw-bold mb-3">Manajemen Folder Galeri</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="get" action="{{ route('admin.galeri.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari nama folder..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.galeri.create') }}" class="btn btn-dark">+ Tambah Folder Baru</a>
        </div>
    </div>

    {{-- Hidden forms untuk toggle & delete (di luar tabel agar tidak nested) --}}
    @foreach($folders as $folder)
        <form id="toggle-form-{{ $folder->id }}" action="{{ route('admin.galeri.toggle', $folder->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        <form id="delete-form-{{ $folder->id }}" action="{{ route('admin.galeri.destroy', $folder->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <div class="table-responsive">
        <table class="table table-bordered align-middle mt-3">
            <thead class="table-dark text-center">
                <tr>
                    <th width="10%">Cover</th>
                    <th width="20%">Nama Folder</th>
                    <th width="22%">Deskripsi</th>
                    <th>Jumlah Foto</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                    <th width="22%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($folders as $folder)
                <tr>
                    <td class="text-center">
                        @if($folder->foto_cover)
                            <img src="{{ asset('img/galeri/' . $folder->foto_cover) }}" alt="Cover" class="img-fluid rounded" style="max-height: 60px;">
                        @else
                            <span class="text-muted small">No Cover</span>
                        @endif
                    </td>
                    <td><strong>{{ $folder->nama_folder }}</strong></td>
                    <td>{{ Str::limit($folder->deskripsi, 50) }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $folder->fotos_count }} Foto</span>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($folder->tanggal_dibuat)->format('d M Y') }}</td>
                    <td class="text-center">
                        {{-- Tombol Toggle Status --}}
                        <button type="button"
                            class="badge border-0 bg-{{ ($folder->status ?? 'publish') == 'publish' ? 'success' : 'secondary' }} text-white"
                            style="cursor:pointer; font-size:.8rem; padding:6px 10px; border-radius:6px;"
                            title="Klik untuk ubah ke {{ ($folder->status ?? 'publish') == 'publish' ? 'Draft' : 'Publish' }}"
                            onclick="document.getElementById('toggle-form-{{ $folder->id }}').submit();">
                            {{ ucfirst($folder->status ?? 'publish') }} ⇄
                        </button>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.galeri.isi', $folder->id) }}" class="btn btn-sm btn-primary mb-1 text-white">Lihat Isi</a>
                        <a href="{{ route('admin.galeri.edit', $folder->id) }}" class="btn btn-sm btn-warning mb-1 text-dark">Edit</a>
                        <button type="button" class="btn btn-sm btn-danger mb-1"
                            onclick="if(confirm('Yakin ingin menghapus folder beserta seluruh foto di dalamnya?')) document.getElementById('delete-form-{{ $folder->id }}').submit();">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4">Data galeri belum tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-end">
        {{ $folders->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection