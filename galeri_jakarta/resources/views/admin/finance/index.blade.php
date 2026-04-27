@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .card-stat { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s; }
    .card-stat:hover { transform: translateY(-5px); }
    .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; }
    .stat-label { font-size: 0.85rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .bg-icon-success { background: #dcfce7; color: #16a34a; }
    .bg-icon-primary { background: #e0e7ff; color: #4f46e5; }
    .bg-icon-warning { background: #fef3c7; color: #d97706; }
    .bg-icon-danger { background: #fee2e2; color: #dc2626; }
    
    .form-control, .form-select { border-radius: 8px !important; border: 1px solid #e2e8f0; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .badge { padding: 6px 10px !important; font-weight: 600 !important; border-radius: 6px !important; }
    
    .bg-success-light { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .bg-warning-light { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .bg-danger-light { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .bg-info-light { background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="fas fa-wallet me-2 text-success"></i> Kelola Keuangan</h3>
        
        <form action="{{ route('admin.finance.sync') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning shadow-sm fw-bold" onclick="return confirm('Mulai sinkronisasi massal? Sistem akan mengecek semua transaksi pending ke API Midtrans.')">
                <i class="fas fa-sync-alt me-1"></i> Sync Pending Transaksi
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stat h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label mb-2">Total Pendapatan</div>
                            <div class="stat-value text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat-icon bg-icon-success"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stat h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label mb-2">Pendapatan Bulan Ini</div>
                            <div class="stat-value text-primary">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat-icon bg-icon-primary"><i class="fas fa-calendar-check"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stat h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label mb-2">Potensi Pending</div>
                            <div class="stat-value text-warning">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat-icon bg-icon-warning"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stat h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label mb-2">Transaksi Gagal</div>
                            <div class="stat-value text-danger">Rp {{ number_format($failedRevenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="stat-icon bg-icon-danger"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & TABLE --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <h5 class="m-0 fw-bold"><i class="fas fa-list me-2"></i> Riwayat Transaksi Keseluruhan</h5>
        </div>
        <div class="card-body bg-light p-3 border-bottom">
            <form method="GET" action="{{ route('admin.finance.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Cari Data</label>
                    <input type="text" name="search" class="form-control" placeholder="Order ID atau Nama User..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil (Settled)</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal / Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Mulai Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Filter</button>
                </div>
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                    <div class="col-md-1">
                        <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary w-100" title="Reset Filter"><i class="fas fa-redo"></i></a>
                    </div>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Order ID & Waktu</th>
                            <th>Pelanggan</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Nominal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark" style="font-family: monospace;">{{ $trx->order_id }}</div>
                                    <div class="small text-muted">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td>
                                    @if($trx->user)
                                        <a href="{{ route('admin.users.show', $trx->user->id) }}" class="fw-bold text-primary text-decoration-none">{{ $trx->user->name }}</a>
                                        <div class="small text-muted">{{ $trx->user->email }}</div>
                                    @else
                                        <span class="text-muted fst-italic">User Dihapus</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trx->payment_type)
                                        <div class="fw-bold text-uppercase" style="font-size: 0.85rem;">{{ str_replace('_', ' ', $trx->payment_type) }}</div>
                                        @if($trx->bank || $trx->va_number)
                                            <div class="small text-muted">{{ strtoupper($trx->bank) }} - {{ $trx->va_number }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold {{ $trx->isSettled() ? 'text-success' : 'text-dark' }}">
                                    Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $trx->statusBadgeClass() }}-light">
                                        {{ $trx->statusLabel() }}
                                    </span>
                                    @if($trx->paid_at)
                                        <div class="small text-muted mt-1" style="font-size:0.7rem;">{{ $trx->paid_at->format('d/m/y H:i') }}</div>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    @if($trx->status === 'pending')
                                        <form action="{{ route('admin.users.check-status', $trx->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm" title="Cek Status Midtrans Manual">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fs-2 mb-3 d-block"></i>
                                    Data transaksi belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
            {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
