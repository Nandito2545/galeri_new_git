@extends('admin.index')

@section('content')
<div class="container-fluid py-4" style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,.08);">
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Tambah Folder Galeri</h3>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Nama Folder</label>
            <input type="text" name="nama_folder" class="form-control" required placeholder="Contoh: Kegiatan Sosial 2024">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tuliskan deskripsi folder..."></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tanggal Dibuat</label>
                <input type="date" name="tanggal_dibuat" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Foto Cover</label>
                <input type="file" name="foto_cover" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG (Maks 2MB).</small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="publish">Publish</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <button type="submit" class="btn btn-dark w-100 text-white mt-3">Simpan Folder</button>
    </form>
</div>
@endsection