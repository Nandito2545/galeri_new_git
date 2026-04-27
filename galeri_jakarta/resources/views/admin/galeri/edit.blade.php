@extends('admin.index')

@section('content')
<div class="container-fluid py-4" style="background: #ffffff; border-radius: 12px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,.08);">
    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">Edit Folder Galeri</h3>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <form action="{{ route('admin.galeri.update', $folder->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div class="mb-3">
            <label class="form-label fw-bold">Nama Folder</label>
            <input type="text" name="nama_folder" class="form-control" required value="{{ old('nama_folder', $folder->nama_folder) }}">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $folder->deskripsi) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tanggal Dibuat</label>
                <input type="date" name="tanggal_dibuat" class="form-control" value="{{ old('tanggal_dibuat', \Carbon\Carbon::parse($folder->tanggal_dibuat)->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ubah Foto Cover</label>
                
                @if($folder->foto_cover)
                    <div class="mb-2">
                        <img src="{{ asset('img/galeri/' . $folder->foto_cover) }}" alt="Cover" class="img-thumbnail" style="max-height:100px">
                    </div>
                @endif
                <input type="file" name="foto_cover" class="form-control" accept="image/*">
                <small class="text-muted">Abaikan jika tidak ingin mengganti cover.</small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select">
                <option value="publish" {{ old('status', $folder->status ?? 'publish') == 'publish' ? 'selected' : '' }}>Publish</option>
                <option value="draft" {{ old('status', $folder->status ?? 'publish') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning w-100 fw-bold mt-3">Update Folder</button>
    </form>
</div>
@endsection