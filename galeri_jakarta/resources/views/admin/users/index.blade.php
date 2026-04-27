@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .container-fluid { background: #ffffff !important; border-radius: 12px !important; padding: 20px !important; box-shadow: 0 8px 24px rgba(0,0,0,.08) !important; }
    .form-control, .form-select { border-radius: 8px !important; }
    .badge { padding: 6px 12px !important; font-weight: 600 !important; border-radius: 6px !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    .avatar-sm { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; }
    .sub-active { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .sub-expired { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .sub-free { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .sub-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="fas fa-users me-2 text-primary"></i> Kelola User & Pelanggan</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 bg-light p-3 mb-4 rounded-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status Langganan</option>
                    <option value="premium" {{ request('status') == 'premium' ? 'selected' : '' }}>Premium Aktif</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Premium Expired</option>
                    <option value="free" {{ request('status') == 'free' ? 'selected' : '' }}>Free User</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            @if(request()->has('search') || request()->has('status'))
                <div class="col-md-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th width="5%" class="text-center">ID</th>
                    <th width="25%">User</th>
                    <th width="15%" class="text-center">Role</th>
                    <th width="15%" class="text-center">Langganan</th>
                    <th width="20%" class="text-center">Expired At</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-center fw-bold text-muted">#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->foto_profil && file_exists(public_path('img/profil/'.$user->foto_profil)))
                                    <img src="{{ asset('img/profil/'.$user->foto_profil) }}" class="avatar-sm me-3" alt="avatar">
                                @else
                                    <div class="avatar-placeholder me-3">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger text-white"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                            @else
                                <span class="badge bg-secondary text-white">User</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->isPremium())
                                <span class="badge sub-active"><i class="fas fa-crown me-1"></i> Premium</span>
                            @elseif($user->subscription === 'premium' && $user->subscription_ends_at && $user->subscription_ends_at->isPast())
                                <span class="badge sub-expired"><i class="fas fa-times-circle me-1"></i> Expired</span>
                            @else
                                <span class="badge sub-free">Free</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($user->subscription === 'premium' && $user->subscription_ends_at)
                                @if($user->subscription_ends_at->isPast())
                                    <span class="text-danger fw-bold">{{ $user->subscription_ends_at->format('d M Y') }}</span>
                                    <div class="small text-danger">(Expired)</div>
                                @elseif($user->isSubscriptionExpiringSoon(7))
                                    <span class="text-warning fw-bold">{{ $user->subscription_ends_at->format('d M Y') }}</span>
                                    <div class="small text-warning">({{ $user->subscription_ends_at->diffForHumans() }})</div>
                                @else
                                    <span class="text-success">{{ $user->subscription_ends_at->format('d M Y') }}</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail & Pembayaran">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            @if($user->role !== 'admin' && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user ini beserta riwayat transaksinya secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fs-2 mb-3 d-block"></i>
                            Data user tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
