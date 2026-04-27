@extends('admin.index')

@section('content')
<style>
body { background-color: #f5f6f8 !important; }
.container-fluid { background: #ffffff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 8px 24px rgba(0,0,0,.08) !important; }
h3 { font-size: 1.6rem !important; letter-spacing: .5px !important; }
.form-control, .form-select { height: 42px !important; border-radius: 8px !important; border: 1px solid #ddd !important; box-shadow: none !important; }
.form-control:focus, .form-select:focus { border-color: #dc3545 !important; box-shadow: 0 0 0 2px rgba(220,53,69,.15) !important; }
.btn { border-radius: 8px !important; padding: 8px 14px !important; font-weight: 500 !important; }
.btn-dark { background: #212529 !important; color:#fff !important; }
.btn-warning { color: #000 !important; }
.table { margin-top: 10px !important; }
.table thead th { font-size: .85rem !important; text-transform: uppercase !important; letter-spacing: .5px !important; text-align: center !important; }
.table tbody td { vertical-align: middle !important; font-size: .9rem !important; }
.table tbody tr:hover { background-color: #f8f9fa !important; }
input[type="checkbox"] { width: 16px !important; height: 16px !important; cursor: pointer !important; }
.badge { padding: 6px 10px !important; font-size: .75rem !important; border-radius: 6px !important; }
select[name="bulk_action"] { min-width: 160px !important; }
</style>

<div class="container-fluid py-4">
    <h3 class="fw-bold mb-3">Daftar Publikasi</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="get" action="{{ route('admin.publikasi.index') }}" class="row g-2 mb-3">
        <div class="col-md-2">
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="Coffeshopia Magz" {{ request('kategori') == 'Coffeshopia Magz' ? 'selected' : '' }}>Coffeshopia Magz</option>
                <option value="E book" {{ request('kategori') == 'E book' ? 'selected' : '' }}>E book</option>
                <option value="Poetri Magz" {{ request('kategori') == 'Poetri Magz' ? 'selected' : '' }}>Poetri Magz</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="judul" class="form-control" placeholder="Cari judul..." value="{{ request('judul') }}">
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="tanggal_publish" {{ request('sort') == 'tanggal_publish' ? 'selected' : '' }}>Terbaru</option>
                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Terbanyak Dilihat</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="limit" class="form-select" onchange="this.form.submit()">
                @foreach([10, 25, 50, 100] as $l)
                    <option value="{{ $l }}" {{ request('limit') == $l ? 'selected' : '' }}>{{ $l }} baris</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('admin.publikasi.create') }}" class="btn btn-dark w-100">+ Tambah Publikasi</a>
        </div>
    </form>

    <p class="text-muted">
        Menampilkan data ke-{{ $publikasi->firstItem() ?? 0 }} sampai {{ $publikasi->lastItem() ?? 0 }} dari total {{ $publikasi->total() }} publikasi
    </p>

    {{-- Hidden forms for toggle & delete (di luar bulkForm) --}}
    @foreach($publikasi as $p)
        <form id="toggle-form-{{ $p->id }}" action="{{ route('admin.publikasi.toggle', $p->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        <form id="delete-form-{{ $p->id }}" action="{{ route('admin.publikasi.destroy', $p->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <form method="POST" action="{{ route('admin.publikasi.bulk') }}" id="bulkForm">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="4%"><input type="checkbox" id="checkAll"></th>
                        <th width="10%">Thumbnail</th>
                        <th width="30%">Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publikasi as $p)
                    <tr>
                        <td class="text-center"><input type="checkbox" name="publikasi_id[]" value="{{ $p->id }}"></td>
                        <td class="text-center">
                            @if($p->thumbnail)
                                <img src="{{ asset('img/publikasi/' . $p->thumbnail) }}" alt="thumbnail" class="img-fluid rounded" style="max-height: 50px;">
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $p->judul }}</strong><br>
                            @if($p->file_pdf)
                                <a href="{{ asset('uploads/publikasi/' . $p->file_pdf) }}" target="_blank" class="text-primary small"><i class="fas fa-file-pdf"></i> Lihat PDF</a>
                            @endif
                        </td>
                        <td>{{ $p->penulis }}</td>
                        <td>{{ $p->tanggal_publish ? \Carbon\Carbon::parse($p->tanggal_publish)->format('d M Y') : '-' }}</td>
                        <td>{{ $p->kategori }}</td>
                        <td class="text-center">
                            {{-- Tombol Toggle Status: submit ke form eksternal --}}
                            <button type="button"
                                class="badge border-0 bg-{{ $p->status == 'publish' ? 'success' : 'secondary' }} text-white"
                                style="cursor:pointer; font-size:.8rem; padding:6px 10px; border-radius:6px;"
                                title="Klik untuk ubah ke {{ $p->status == 'publish' ? 'Draft' : 'Publish' }}"
                                onclick="document.getElementById('toggle-form-{{ $p->id }}').submit();">
                                {{ ucfirst($p->status) }} ⇄
                            </button>
                        </td>
                        <td class="text-center">{{ $p->views }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.publikasi.edit', $p->id) }}" class="btn btn-sm btn-warning text-dark mb-1">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger mb-1"
                                onclick="if(confirm('Yakin hapus publikasi ini secara permanen?')) document.getElementById('delete-form-{{ $p->id }}').submit();">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">Data publikasi tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publikasi->count() > 0)
        <div class="d-flex gap-2 mt-3">
            <select name="bulk_action" class="form-select w-auto">
                <option value="">Pilih Aksi Massal...</option>
                <option value="publish">Ubah ke Publish</option>
                <option value="draft">Ubah ke Draft</option>
                <option value="delete">Hapus Permanen</option>
            </select>
            <button class="btn btn-danger" type="button" onclick="submitBulkForm()">Terapkan Aksi</button>
        </div>
        @endif
    </form>

    <div class="mt-4 d-flex justify-content-end">
        {{ $publikasi->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    document.getElementById('checkAll').onclick = function() {
        document.querySelectorAll('input[name="publikasi_id[]"]').forEach(cb => cb.checked = this.checked);
    }

    function submitBulkForm() {
        var action = document.querySelector('select[name="bulk_action"]').value;
        var checked = document.querySelectorAll('input[name="publikasi_id[]"]:checked').length;

        if (action === "") {
            alert("Silakan pilih aksi massal terlebih dahulu!");
            return;
        }
        if (checked === 0) {
            alert("Silakan pilih minimal satu publikasi!");
            return;
        }
        if (confirm('Yakin ingin menerapkan aksi ini ke ' + checked + ' data?')) {
            document.getElementById('bulkForm').submit();
        }
    }
</script>
@endsection
