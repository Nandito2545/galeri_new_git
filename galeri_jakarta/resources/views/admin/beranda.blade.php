@extends('admin.index')

@section('content')
    <style>
        /* Hover umum untuk semua tombol */
        a.btn {
            transition: all 0.2s ease-in-out;
        }

        a.btn-dark:hover {
            background-color: rgb(3, 104, 206) !important;
            color: rgb(255, 255, 255) !important;
            transform: scale(1.03);
        }

        a.btn-outline-dark:hover {
            background-color: #212529 !important;
            color: #fff !important;
            transform: scale(1.03);
        }

        a.btn-outline-secondary:hover {
            background-color: #6c757d !important;
            color: #fff !important;
            transform: scale(1.03);
        }

        a.btn-secondary:hover {
            background-color: #5a6268 !important;
            color: #fff !important;
            transform: scale(1.03);
        }

        a.btn-danger:hover {
            background-color: #c82333 !important;
            color: #fff !important;
            transform: scale(1.03);
        }

        a:hover {
            text-decoration: none;
            opacity: 0.9;
        }
    </style>

    <div class="container-fluid py-4">
        <h3 class="fw-bold mb-4">Dashboard Admin GBJ</h3>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="c text-dark p-3 shadow-sm border rounded bg-white">
                    <h5 class="mb-1">Tambah Artikel</h5>
                    <p class="mb-3">Tulis dan publikasikan artikel baru.</p>
                    <a href="{{ url('/admin/artikel/tambah') }}" class="btn btn-dark btn-sm">Tambah</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="c text-dark p-3 shadow-sm border rounded bg-white">
                    <h5 class="mb-1">Tambah Galeri</h5>
                    <p class="mb-3">Buat folder galeri dan unggah gambar.</p>
                    <a href="{{ url('/admin/galeri/tambah') }}" class="btn btn-dark btn-sm">Tambah</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="c text-dark p-3 shadow-sm border rounded bg-white">
                    <h5 class="mb-1">
                        Pesan Masuk
                        @if ($notif_pesan > 0)
                            <span class="badge bg-danger">{{ $notif_pesan }}</span>
                        @endif
                    </h5>
                    <p class="mb-3">Cek semua pesan dari pengguna.</p>
                    <a href="{{ url('/admin/pesan') }}" class="btn btn-dark btn-sm">
                        Lihat
                        @if ($notif_pesan > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $notif_pesan }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 mb-4 rounded shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Artikel Terbaru</h5>
                <a href="{{ url('/admin/artikel') }}" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
            </div>
            @foreach ($artikel as $a)
                <div class="border-bottom py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $a->judul }}</strong><br>
                            <small>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }} - {{ $a->penulis }}</small>
                        </div>
                        <a href="{{ url('/admin/artikel/detail/' . $a->id) }}"
                            class="btn btn-sm btn-outline-secondary">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white p-4 mb-4 rounded shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    Pesan Terbaru
                    @if ($notif_pesan > 0)
                        <span class="badge bg-danger ms-2">{{ $notif_pesan }} belum dibaca</span>
                    @endif
                </h5>
                <a href="{{ url('/admin/pesan') }}" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
            </div>

            @foreach ($pesan as $p)
                <div
                    class="border-bottom py-2 {{ $p->status_baca == 'belum' ? 'bg-light border-start border-4 border-warning px-2' : '' }}">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $p->nama }}</strong>
                            @if ($p->status_baca == 'belum')
                                <span class="badge bg-warning text-dark ms-1">Belum dibaca</span>
                            @endif
                            <br>
                            <small>{{ \Carbon\Carbon::parse($p->tanggal_kirim)->format('d M Y H:i') }}</small><br>
                            <span>{{ \Illuminate\Support\Str::limit($p->isi_pesan, 60) }}</span>
                        </div>
                        <a href="{{ url('/admin/pesan/detail/' . $p->id) }}"
                            class="btn btn-sm btn-outline-secondary">Baca</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white p-4 rounded shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Galeri Foto Terbaru</h5>
                <a href="{{ url('/admin/galeri') }}" class="btn btn-sm btn-outline-dark">Kelola Galeri</a>
            </div>
            <div class="row">
                @foreach ($galeri as $g)
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border rounded h-100">
                            <h6 class="mb-1">{{ $g->nama_galeri }}</h6>
                            <p class="mb-1">{!! nl2br(e($g->deskripsi)) !!}</p>
                            <small class="text-muted">Dibuat:
                                {{ \Carbon\Carbon::parse($g->tanggal_dibuat)->format('d M Y') }}</small><br>
                            <a href="{{ url('/admin/galeri/isi/' . $g->id) }}"
                                class="btn btn-sm btn-outline-secondary mt-2">Lihat</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
