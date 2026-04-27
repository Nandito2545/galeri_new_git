@extends('admin.index') 

@section('content')
<style>
/* Styling menyesuaikan dengan tema admin Anda sebelumnya */
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
.text-muted { font-size: .9rem !important; }

@media(max-width:768px){
  .table thead { display: none !important; }
  .table tbody tr { display: block !important; margin-bottom: 15px !important; border: 1px solid #ddd !important; border-radius: 8px !important; padding: 10px !important; }
  .table tbody td { display: flex !important; justify-content: space-between !important; padding: 6px 10px !important; border: none !important; }
}
</style>

<div class="container-fluid py-4">

    <h3 class="fw-bold mb-3">Daftar Artikel</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="get" action="{{ route('admin.artikel.index') }}" class="row g-2 mb-3">
        <div class="col-md-2">
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="Buku" {{ request('kategori') == 'Buku' ? 'selected' : '' }}>Buku</option>
                <option value="Kata" {{ request('kategori') == 'Kata' ? 'selected' : '' }}>Kata</option>
                <option value="Kota" {{ request('kategori') == 'Kota' ? 'selected' : '' }}>Kota</option>
                <option value="Inspirasi" {{ request('kategori') == 'Inspirasi' ? 'selected' : '' }}>Inspirasi</option>
                <option value="Gairah" {{ request('kategori') == 'Gairah' ? 'selected' : '' }}>Gairah</option>
                <option value="Cerita" {{ request('kategori') == 'Cerita' ? 'selected' : '' }}>Cerita</option>
                <option value="Pustaka" {{ request('kategori') == 'Pustaka' ? 'selected' : '' }}>Pustaka</option>
                <option value="Essai" {{ request('kategori') == 'Essai' ? 'selected' : '' }}>Essai</option>
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
            <a href="{{ route('admin.artikel.create') }}" class="btn btn-dark w-100">+ Tambah Artikel</a>
        </div>
    </form>

    <p class="text-muted">
        Menampilkan data ke-{{ $articles->firstItem() ?? 0 }} sampai {{ $articles->lastItem() ?? 0 }} dari total {{ $articles->total() }} artikel
    </p>

    {{-- Hidden forms for toggle & delete (di luar bulkForm) --}}
    @foreach($articles as $a)
        {{-- Form Toggle Status --}}
        <form id="toggle-form-{{ $a->id }}" action="{{ route('admin.artikel.toggle', $a->id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        {{-- Form Hapus --}}
        <form id="delete-form-{{ $a->id }}" action="{{ route('admin.artikel.destroy', $a->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Tabel dengan Bulk Form --}}
    <form method="POST" action="{{ route('admin.artikel.bulk') }}" id="bulkForm">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%"><input type="checkbox" id="checkAll"></th>
                        <th width="10%">Thumbnail</th>
                        <th width="30%">Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal Publish</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $a)
                    <tr>
                        <td class="text-center"><input type="checkbox" name="artikel_id[]" value="{{ $a->id }}"></td>
                        
                        <td class="text-center">
                            @if($a->thumbnail)
                                <img src="{{ asset('img/' . $a->thumbnail) }}" alt="thumbnail" class="img-fluid rounded" style="max-height: 50px;">
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $a->judul }}</strong><br>
                            <span class="text-muted small">Slug: {{ $a->slug }}</span>
                        </td>
                        <td>{{ $a->penulis }}</td>
                        <td>{{ $a->tanggal_publish ? \Carbon\Carbon::parse($a->tanggal_publish)->format('d M Y') : '-' }}</td>
                        <td>{{ $a->kategori }}</td>
                        <td class="text-center">
                            {{-- Tombol Toggle Status: submit ke form eksternal --}}
                            <button type="button"
                                class="badge border-0 bg-{{ $a->status == 'publish' ? 'success' : 'secondary' }} text-white"
                                style="cursor:pointer; font-size:.8rem; padding:6px 10px; border-radius:6px;"
                                title="Klik untuk ubah ke {{ $a->status == 'publish' ? 'Draft' : 'Publish' }}"
                                onclick="document.getElementById('toggle-form-{{ $a->id }}').submit();">
                                {{ ucfirst($a->status) }} ⇄
                            </button>
                        </td>
                        <td class="text-center">{{ $a->views }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.artikel.edit', $a->id) }}" class="btn btn-sm btn-warning text-dark mb-1">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger mb-1"
                                onclick="if(confirm('Yakin hapus artikel ini secara permanen?')) document.getElementById('delete-form-{{ $a->id }}').submit();">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">Data artikel tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->count() > 0)
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
        {{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>

<script>
    // Check All / Uncheck All
    document.getElementById('checkAll').onclick = function() {
        document.querySelectorAll('input[name="artikel_id[]"]').forEach(cb => cb.checked = this.checked);
    }

    // Validasi sebelum submit Bulk Action
    function submitBulkForm() {
        var action = document.querySelector('select[name="bulk_action"]').value;
        var checked = document.querySelectorAll('input[name="artikel_id[]"]:checked').length;
        
        if (action === "") {
            alert("Silakan pilih aksi massal terlebih dahulu!");
            return;
        }
        if (checked === 0) {
            alert("Silakan pilih minimal satu artikel!");
            return;
        }
        if (confirm('Yakin ingin menerapkan aksi ini ke ' + checked + ' artikel yang dipilih?')) {
            document.getElementById('bulkForm').submit();
        }
    }
</script>
@endsection