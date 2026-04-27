@extends('admin.index')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Isi Galeri: {{ $folder->nama_folder }}</h3>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Kembali ke Folder</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-white">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Form Tambah Foto -->
        <div class="col-md-4 mb-4">
            <div class="bg-white p-4 rounded shadow-sm border">
                <h5 class="mb-3">Tambah Foto Baru</h5>
                <form action="{{ route('admin.galeri.isi.store', $folder->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Foto <span class="text-danger">*</span></label>
                        <input type="text" name="nama_foto" class="form-control border px-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Foto <span class="text-danger">*</span></label>
                        <input type="file" name="file_foto" class="form-control border px-2" accept="image/*" required>
                        <small class="text-muted">Maksimal 2MB (JPG, PNG, WEBP)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control border px-2" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Upload Foto</button>
                </form>
            </div>
        </div>

        <!-- Daftar Foto -->
        <div class="col-md-8">
            <div class="bg-white p-4 rounded shadow-sm border">
                <h5 class="mb-3">Daftar Foto</h5>
                
                <div class="row">
                    @forelse($fotos as $foto)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('img/galeri/isi/' . $foto->file_foto) }}" class="card-img-top" alt="{{ $foto->nama_foto }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1 text-truncate" title="{{ $foto->nama_foto }}">{{ $foto->nama_foto }}</h6>
                                    <p class="card-text small text-muted text-truncate" title="{{ $foto->deskripsi }}">{{ $foto->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                                <div class="card-footer p-2 bg-white border-top-0 d-flex justify-content-between align-items-center">
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($foto->tanggal_upload)->format('d M Y') }}</small>
                                    <form action="{{ route('admin.galeri.isi.destroy', $foto->id) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger py-1 px-2"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4">
                            <p class="text-muted mb-0">Belum ada foto dalam folder ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
