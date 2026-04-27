<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6f8; font-family: 'Instrument Sans', sans-serif; }
        .profile-container { max-width: 900px; margin: 50px auto; padding: 20px; }
        
        .card-custom {
            background: #fff; border-radius: 12px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 25px;
        }
        .card-header-custom { background: #fff; border-bottom: 1px solid #eee; padding: 20px 25px; border-radius: 12px 12px 0 0; }
        .card-header-custom h5 { margin: 0; font-weight: bold; color: #333; }
        
        .profile-header { display: flex; align-items: center; gap: 20px; padding: 30px 25px; }
        .avatar-huge {
            width: 100px; height: 100px; border-radius: 50%; background: #212529; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold;
        }
        .profile-info h3 { margin: 0 0 5px; font-weight: bold; }
        .profile-info p { margin: 0; color: #666; font-size: 16px; }

        .info-row { display: flex; padding: 15px 0; border-bottom: 1px solid #f1f1f1; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 30%; color: #666; font-weight: 500; }
        .info-value { width: 70%; color: #222; font-weight: 600; }

        .form-control { border-radius: 8px; padding: 12px 15px; border: 1px solid #ddd; }
        .form-control:focus { box-shadow: none; border-color: #212529; }
        .btn-dark { background: #212529; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; }

        .nav-pills .nav-link { color: #555; font-weight: 500; border-radius: 8px; padding: 10px 20px; margin-bottom: 10px; }
        .nav-pills .nav-link.active { background: #212529; color: #fff; }
        .nav-pills .nav-link i { margin-right: 8px; }
    </style>
</head>
<body>

<!-- INCLUDA NAVBAR -->
@include('components.user_navbar')

<div class="container profile-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Account</h2>
        <a href="{{ route('beranda') }}" class="btn btn-outline-dark" style="border-radius: 8px;">← Kembali ke Beranda</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- SIDEBAR TABS -->
        <div class="col-md-3">
            <div class="card-custom p-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start" id="v-pills-detail-tab" data-bs-toggle="pill" data-bs-target="#v-pills-detail" type="button" role="tab">
                        <i class="bi bi-person-vcard"></i> Detail Profile
                    </button>
                    <button class="nav-link text-start" id="v-pills-edit-tab" data-bs-toggle="pill" data-bs-target="#v-pills-edit" type="button" role="tab">
                        <i class="bi bi-pencil-square"></i> Kelola Profile
                    </button>
                    <button class="nav-link text-start" id="v-pills-langganan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-langganan" type="button" role="tab">
                        <i class="bi bi-card-checklist"></i> Status Langganan
                    </button>
                </div>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="col-md-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- DETAIL PROFILE -->
                <div class="tab-pane fade show active" id="v-pills-detail" role="tabpanel" tabindex="0">
                    <div class="card-custom">
                        <div class="profile-header">
                            <div class="avatar-huge">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="profile-info">
                                <h3>{{ Auth::user()->name }}</h3>
                                <p>{{ Auth::user()->email }}</p>
                                <div class="mt-2">
                                    @if(Auth::user()->subscription === 'premium')
                                        <span class="badge bg-warning text-dark px-3 py-2" style="border-radius:20px; font-size:13px;"><i class="bi bi-star-fill"></i> Premium Member</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2" style="border-radius:20px; font-size:13px;">Free Account</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-custom">
                        <div class="card-header-custom"><h5>Informasi Pribadi</h5></div>
                        <div class="p-4">
                            <div class="info-row">
                                <div class="info-label">Nama Lengkap</div>
                                <div class="info-value">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Bergabung Sejak</div>
                                <div class="info-value">{{ Auth::user()->created_at->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KELOLA PROFILE -->
                <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" tabindex="0">
                    <div class="card-custom">
                        <div class="card-header-custom"><h5>Kelola Profile</h5></div>
                        <div class="p-4">
                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted fw-bold">Email Address</label>
                                    <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" disabled readonly>
                                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle"></i> Alamat email tidak dapat diubah setelah terdaftar.</small>
                                </div>
                                <hr class="my-4">
                                <h6>Ubah Password <small class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</small></h6>
                                <div class="row mt-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted fw-bold">Password Baru</label>
                                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted fw-bold">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                                    </div>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- STATUS LANGGANAN -->
                <div class="tab-pane fade" id="v-pills-langganan" role="tabpanel" tabindex="0">
                    <div class="card-custom">
                        <div class="card-header-custom"><h5>Status Langganan</h5></div>
                        <div class="p-4 text-center">
                            @if(Auth::user()->subscription === 'premium')
                                <i class="bi bi-patch-check-fill text-warning" style="font-size: 80px;"></i>
                                <h3 class="fw-bold mt-2">Premium Aktif</h3>
                                <p class="text-muted mb-4">Anda memiliki akses ke semua konten eksklusif kami.</p>
                                
                                <div class="alert alert-warning text-dark text-start" style="border-radius: 12px; background:#fff8e1; border:1px solid #ffe082;">
                                    <div class="info-row border-0 py-2">
                                        <div class="info-label w-50">Paket Saat Ini</div>
                                        <div class="info-value w-50 fw-bold">Premium Tahunan</div>
                                    </div>
                                    <div class="info-row border-0 py-2">
                                        <div class="info-label w-50">Berakhir Pada</div>
                                        <div class="info-value w-50 fw-bold text-danger">
                                            {{ Auth::user()->subscription_ends_at ? \Carbon\Carbon::parse(Auth::user()->subscription_ends_at)->format('d F Y') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <i class="bi bi-box-seam text-secondary" style="font-size: 80px;"></i>
                                <h3 class="fw-bold mt-2">Akun Gratis</h3>
                                <p class="text-muted mb-4">Anda saat ini menggunakan akun gratis dengan akses terbatas.</p>
                                <a href="{{ route('payment.page') }}" class="btn btn-warning text-dark fw-bold px-4 py-2" style="border-radius: 8px;">Upgrade ke Premium Sekarang</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Buka tab berdasarkan hash URL (misal: #edit, #langganan)
    document.addEventListener("DOMContentLoaded", function() {
        let hash = window.location.hash;
        if(hash) {
            let tabBtn = document.querySelector(`button[data-bs-target="${hash}"]`);
            if(tabBtn) {
                let tab = new bootstrap.Tab(tabBtn);
                tab.show();
            }
        }
    });
</script>
</body>
</html>
