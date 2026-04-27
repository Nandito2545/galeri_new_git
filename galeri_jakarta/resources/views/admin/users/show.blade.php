@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .card-header { background: #fff !important; border-bottom: 1px solid #e2e8f0; font-weight: 700; }
    .avatar-lg { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; }
    .avatar-placeholder-lg { width: 80px; height: 80px; border-radius: 50%; background: #1e293b; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 2rem; border: 3px solid #e2e8f0; }
    .badge { padding: 6px 12px !important; font-weight: 600 !important; border-radius: 6px !important; }
    .info-label { font-size: 0.85rem; color: #64748b; font-weight: 600; margin-bottom: 2px; }
    .info-value { font-size: 1rem; color: #1e293b; font-weight: 500; }
    
    /* Transaction status badges */
    .bg-success-light { background-color: #dcfce7; color: #166534; }
    .bg-warning-light { background-color: #fef3c7; color: #92400e; }
    .bg-danger-light { background-color: #fee2e2; color: #991b1b; }
    .bg-secondary-light { background-color: #f1f5f9; color: #475569; }
    .bg-info-light { background-color: #e0f2fe; color: #075985; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary mb-2"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
            <h3 class="fw-bold m-0">Detail User: {{ $user->name }}</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-check-circle me-1"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: Info Profil & Subscription --}}
        <div class="col-lg-4">
            
            {{-- Card Profil Dasar --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="d-flex justify-content-center mb-3">
                        @if($user->foto_profil && file_exists(public_path('img/profil/'.$user->foto_profil)))
                            <img src="{{ asset('img/profil/'.$user->foto_profil) }}" class="avatar-lg" alt="avatar">
                        @else
                            <div class="avatar-placeholder-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <div class="text-muted mb-3">{{ $user->email }}</div>
                    
                    @if($user->role === 'admin')
                        <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i> Admin</span>
                    @else
                        <span class="badge bg-secondary"><i class="fas fa-user me-1"></i> User</span>
                    @endif

                    <hr class="my-4">

                    <div class="text-start">
                        <div class="mb-3">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $user->no_telepon ?? 'Belum diisi' }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label">Terdaftar Sejak</div>
                            <div class="info-value">{{ $user->created_at->format('d F Y H:i') }}</div>
                        </div>
                        <div class="mb-0">
                            <div class="info-label">Bio</div>
                            <div class="info-value">{{ $user->bio ?? 'Belum diisi' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Langganan (Override) --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <i class="fas fa-crown text-warning me-2"></i> Status Langganan (Override)
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        Status Saat Ini: 
                        @if($user->isPremium())
                            <span class="badge bg-success ms-2">Premium Aktif</span>
                        @elseif($user->subscription === 'premium' && $user->subscription_ends_at && $user->subscription_ends_at->isPast())
                            <span class="badge bg-danger ms-2">Premium Expired</span>
                        @else
                            <span class="badge bg-secondary ms-2">Free</span>
                        @endif
                    </div>

                    <form action="{{ route('admin.users.update-subscription', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengubah status langganan secara manual?')">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ubah Status</label>
                            <select name="subscription" class="form-select" id="subSelect">
                                <option value="free" {{ $user->subscription == 'free' ? 'selected' : '' }}>Free</option>
                                <option value="premium" {{ $user->subscription == 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        <div class="mb-3" id="expDateGroup" style="display: {{ $user->subscription == 'premium' ? 'block' : 'none' }}">
                            <label class="form-label fw-bold">Tanggal Expired</label>
                            <input type="datetime-local" name="subscription_ends_at" class="form-control" 
                                   value="{{ $user->subscription_ends_at ? $user->subscription_ends_at->format('Y-m-d\TH:i') : now()->addYear()->format('Y-m-d\TH:i') }}">
                            <small class="text-muted">Set kapan akses premium akan berakhir.</small>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                            <i class="fas fa-save me-1"></i> Update Manual
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Transaksi / Log Midtrans --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-history text-primary me-2"></i> Riwayat Transaksi Midtrans</div>
                    
                    {{-- Form Buat Pembayaran Baru --}}
                    <form action="{{ route('admin.users.create-payment', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" title="Generate Snap Token baru untuk user ini" onclick="return confirm('Generate tagihan Rp 49.000 baru untuk user ini?')">
                            <i class="fas fa-plus me-1"></i> Buat Tagihan Baru
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Order ID / Waktu</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th class="text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->paymentLogs as $log)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark" style="font-family: monospace;">{{ $log->order_id }}</div>
                                            <div class="small text-muted">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td class="fw-bold text-success">Rp {{ number_format($log->gross_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($log->payment_type)
                                                <div class="fw-bold text-uppercase" style="font-size: 0.85rem;">{{ str_replace('_', ' ', $log->payment_type) }}</div>
                                                @if($log->bank || $log->va_number)
                                                    <div class="small text-muted">{{ strtoupper($log->bank) }} - {{ $log->va_number }}</div>
                                                @endif
                                            @else
                                                <span class="text-muted small">Belum dipilih</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $log->statusBadgeClass() }}-light border border-{{ $log->statusBadgeClass() }}">
                                                {{ $log->statusLabel() }}
                                            </span>
                                            @if($log->paid_at)
                                                <div class="small text-muted mt-1">{{ $log->paid_at->format('d/m/y H:i') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            {{-- Tombol Cek Status Manual Midtrans --}}
                                            <form action="{{ route('admin.users.check-status', $log->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Cek status terbaru dari Midtrans">
                                                    <i class="fas fa-sync-alt"></i> Cek
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-receipt fs-3 mb-3 d-block text-light"></i>
                                            Belum ada riwayat transaksi pembayaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('subSelect').addEventListener('change', function() {
        if(this.value === 'premium') {
            document.getElementById('expDateGroup').style.display = 'block';
        } else {
            document.getElementById('expDateGroup').style.display = 'none';
        }
    });
</script>
@endsection
