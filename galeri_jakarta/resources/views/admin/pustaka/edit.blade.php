@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .container-fluid { background: #ffffff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 8px 24px rgba(0,0,0,.08) !important; }
    .form-label { font-weight: 600; color: #444; }
    .form-control, .form-select { border-radius: 8px !important; border: 1px solid #ddd !important; box-shadow: none !important; }
    .form-control:focus, .form-select:focus { border-color: #dc3545 !important; box-shadow: 0 0 0 2px rgba(220,53,69,.15) !important; }
    .btn { border-radius: 8px !important; padding: 10px 20px !important; font-weight: 500 !important; }
    .card-section { background: #fafbfe; padding: 15px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Edit Pustaka (Buku)</h3>
        <a href="{{ route('admin.pustaka.index') }}" class="btn btn-secondary">← Kembali</a>
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

    <form action="{{ route('admin.pustaka.update', $pustaka->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- KOLOM KIRI (Info Utama) --}}
            <div class="col-md-8">
                <div class="card-section">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Utama</h5>
                    <div class="mb-3">
                        <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $pustaka->judul) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penulis <span class="text-danger">*</span></label>
                            <input type="text" name="penulis" class="form-control" value="{{ old('penulis', $pustaka->penulis) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit', $pustaka->penerbit) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" value="{{ old('harga', $pustaka->harga) }}" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit', $pustaka->tanggal_terbit ? \Carbon\Carbon::parse($pustaka->tanggal_terbit)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $pustaka->isbn) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Buku</label>
                        <textarea name="deskripsi" class="form-control" rows="8">{{ old('deskripsi', $pustaka->deskripsi) }}</textarea>
                    </div>
                </div>

                <div class="card-section">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Spesifikasi Fisik</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jml Halaman</label>
                            <input type="number" name="halaman" class="form-control" value="{{ old('halaman', $pustaka->halaman) }}" min="1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Panjang (cm)</label>
                            <input type="number" step="0.01" name="panjang" class="form-control" value="{{ old('panjang', $pustaka->panjang) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Lebar (cm)</label>
                            <input type="number" step="0.01" name="lebar" class="form-control" value="{{ old('lebar', $pustaka->lebar) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Berat (gram)</label>
                            <input type="number" step="0.01" name="berat" class="form-control" value="{{ old('berat', $pustaka->berat) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Kategori, Media & Status) --}}
            <div class="col-md-4">
                <div class="card-section">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Pengaturan Publikasi</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="Pasar Buku" {{ old('kategori', $pustaka->kategori) == 'Pasar Buku' ? 'selected' : '' }}>Pasar Buku</option>
                            <option value="Galeri Buku" {{ old('kategori', $pustaka->kategori) == 'Galeri Buku' ? 'selected' : '' }}>Galeri Buku</option>
                            <option value="Terlaris" {{ old('kategori', $pustaka->kategori) == 'Terlaris' ? 'selected' : '' }}>Terlaris</option>
                            <option value="Akan Terbit" {{ old('kategori', $pustaka->kategori) == 'Akan Terbit' ? 'selected' : '' }}>Akan Terbit</option>
                            <option value="Koleksi" {{ old('kategori', $pustaka->kategori) == 'Koleksi' ? 'selected' : '' }}>Koleksi</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bahasa</label>
                        <input type="text" name="bahasa" class="form-control" value="{{ old('bahasa', $pustaka->bahasa) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="publish" {{ old('status', $pustaka->status) == 'publish' ? 'selected' : '' }}>Publish</option>
                            <option value="draft" {{ old('status', $pustaka->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Ubah Cover Buku (Thumbnail)</label>
                        @if($pustaka->thumbnail)
                            <div class="mb-2">
                                <img src="{{ asset('img/pustaka/' . $pustaka->thumbnail) }}" alt="Cover" class="img-thumbnail" style="max-height:150px">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah cover.</small>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">Update Buku</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
