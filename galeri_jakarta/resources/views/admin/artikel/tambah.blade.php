@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .container-fluid { background: #ffffff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 8px 24px rgba(0,0,0,.08) !important; }
    h3 { font-size: 1.6rem !important; letter-spacing: .5px !important; }
    .form-label { font-weight: 600; color: #444; }
    .form-control, .form-select { border-radius: 8px !important; border: 1px solid #ddd !important; box-shadow: none !important; }
    .form-control:focus, .form-select:focus { border-color: #dc3545 !important; box-shadow: 0 0 0 2px rgba(220,53,69,.15) !important; }
    .btn { border-radius: 8px !important; padding: 10px 20px !important; font-weight: 500 !important; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Tambah Artikel Baru</h3>
        <a href="{{ route('admin.artikel.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger text-white">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Masukkan judul artikel..." required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ringkasan (Summary) <span class="text-danger">*</span></label>
                    <textarea name="ringkasan" class="form-control" rows="3" placeholder="Tuliskan ringkasan singkat..." required>{{ old('ringkasan') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Artikel Lengkap <span class="text-danger">*</span></label>
                    <textarea name="isi" class="form-control" rows="10" placeholder="Tuliskan isi artikel di sini..." required>{{ old('isi') }}</textarea>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Buku" {{ old('kategori') == 'Buku' ? 'selected' : '' }}>Buku</option>
                        <option value="Kata" {{ old('kategori') == 'Kata' ? 'selected' : '' }}>Kata</option>
                        <option value="Kota" {{ old('kategori') == 'Kota' ? 'selected' : '' }}>Kota</option>
                        <option value="Inspirasi" {{ old('kategori') == 'Inspirasi' ? 'selected' : '' }}>Inspirasi</option>
                        <option value="Gairah" {{ old('kategori') == 'Gairah' ? 'selected' : '' }}>Gairah</option>
                        <option value="Cerita" {{ old('kategori') == 'Cerita' ? 'selected' : '' }}>Cerita</option>
                        <option value="Pustaka" {{ old('kategori') == 'Pustaka' ? 'selected' : '' }}>Pustaka</option>
                        <option value="Essai" {{ old('kategori') == 'Essai' ? 'selected' : '' }}>Essai</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Penulis <span class="text-danger">*</span></label>
                    <input type="text" name="penulis" class="form-control" value="{{ old('penulis') ?? auth()->user()->name ?? 'Admin' }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Publish <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_publish" class="form-control" value="{{ old('tanggal_publish') ?? date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Thumbnail Artikel</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, WEBP (Max 2MB)</small>
                </div>

                <button type="submit" class="btn btn-dark w-100 text-white">Simpan Artikel</button>
            </div>
        </div>
    </form>
</div>
@endsection