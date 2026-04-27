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
    <h3 class="fw-bold mb-3">Daftar Pustaka (Buku)</h3>

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

    <form method="get" action="{{ route('admin.pustaka.index') }}" class="row g-2 mb-3">
        <div class="col-md-2">
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="Pasar Buku" {{ request('kategori') == 'Pasar Buku' ? 'selected' : '' }}>Pasar Buku</option>
                <option value="Galeri Buku" {{ request('kategori') == 'Galeri Buku' ? 'selected' : '' }}>Galeri Buku</option>
                <option value="Terlaris" {{ request('kategori') == 'Terlaris' ? 'selected' : '' }}>Terlaris</option>
                <option value="Akan Terbit" {{ request('kategori') == 'Akan Terbit' ? 'selected' : '' }}>Akan Terbit</option>
                <option value="Koleksi" {{ request('kategori') == 'Koleksi' ? 'selected' : '' }}>Koleksi</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="judul" class="form-control" placeholder="Cari judul..." value="{{ request('judul') }}">
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
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
            <a href="{{ route('admin.pustaka.create') }}" class="btn btn-dark w-100">+ Tambah Pustaka</a>
        </div>
    </form>

    <p class="text-muted">
        Menampilkan data ke-{{ $pustakas->firstItem() ?? 0 }} sampai {{ $pustakas->lastItem() ?? 0 }} dari total {{ $pustakas->total() }} pustaka
    </p>

    {{-- Hidden forms for toggle & delete --}}
    @foreach($pustakas as $p)
        <form id="toggle-form-{{ $p->id }}" action="{{ route('admin.pustaka.toggle', $p->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        <form id="delete-form-{{ $p->id }}" action="{{ route('admin.pustaka.destroy', $p->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <form method="POST" action="{{ route('admin.pustaka.bulk') }}" id="bulkForm">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="4%"><input type="checkbox" id="checkAll"></th>
                        <th width="8%">Cover</th>
                        <th width="25%">Judul Buku</th>
                        <th>Penulis</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pustakas as $p)
                    <tr>
                        <td class="text-center"><input type="checkbox" name="pustaka_id[]" value="{{ $p->id }}"></td>
                        <td class="text-center">
                            @if($p->thumbnail)
                                <img src="{{ asset('img/pustaka/' . $p->thumbnail) }}" alt="cover" class="img-fluid rounded" style="max-height: 60px;">
                            @else
                                <span class="text-muted small">No Cover</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $p->judul }}</strong><br>
                            <span class="text-muted small">ISBN: {{ $p->isbn ?? '-' }}</span>
                        </td>
                        <td>{{ $p->penulis }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $p->kategori }}</td>
                        <td class="text-center">
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
                            <a href="{{ route('admin.pustaka.edit', $p->id) }}" class="btn btn-sm btn-warning text-dark mb-1 w-100">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger mb-1 w-100"
                                onclick="if(confirm('Yakin hapus buku ini secara permanen?')) document.getElementById('delete-form-{{ $p->id }}').submit();">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Data pustaka tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pustakas->count() > 0)
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
        {{ $pustakas->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    document.getElementById('checkAll').onclick = function() {
        document.querySelectorAll('input[name="pustaka_id[]"]').forEach(cb => cb.checked = this.checked);
    }

    function submitBulkForm() {
        var action = document.querySelector('select[name="bulk_action"]').value;
        var checked = document.querySelectorAll('input[name="pustaka_id[]"]:checked').length;

        if (action === "") {
            alert("Silakan pilih aksi massal terlebih dahulu!");
            return;
        }
        if (checked === 0) {
            alert("Silakan pilih minimal satu buku!");
            return;
        }
        if (confirm('Yakin ingin menerapkan aksi ini ke ' + checked + ' data?')) {
            document.getElementById('bulkForm').submit();
        }
    }
</script>
@endsection
