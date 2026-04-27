@extends('admin.index')

@section('content')
<style>
    body { background-color: #f5f6f8 !important; }
    .profile-card { background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.08); overflow: hidden; }
    .profile-header { background: linear-gradient(135deg, #1a499c 0%, #2563eb 100%); padding: 40px 30px 80px; position: relative; }
    .profile-avatar-wrap { position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); }
    .profile-avatar { width: 100px; height: 100px; border-radius: 50%; border: 4px solid #fff; object-fit: cover; box-shadow: 0 4px 16px rgba(0,0,0,.2); }
    .avatar-placeholder { width: 100px; height: 100px; border-radius: 50%; border: 4px solid #fff; background: #1a499c; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: white; font-weight: 700; box-shadow: 0 4px 16px rgba(0,0,0,.2); }
    .profile-body { padding: 70px 30px 30px; }
    .profile-name { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
    .profile-role { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .8rem; font-weight: 600; margin-top: 4px; }
    .role-admin { background: #fef3c7; color: #d97706; }
    .info-card { background: #f8fafc; border-radius: 12px; padding: 20px; margin-top: 20px; border: 1px solid #e2e8f0; }
    .info-row { display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
    .info-row:last-child { border-bottom: none; }
    .info-label { width: 140px; font-size: .85rem; color: #64748b; font-weight: 600; flex-shrink: 0; }
    .info-value { font-size: .95rem; color: #1e293b; }
    .section-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
    .form-control, .form-select { border-radius: 8px !important; border: 1px solid #e2e8f0 !important; padding: 10px 14px !important; font-size: .9rem; }
    .form-control:focus { border-color: #2563eb !important; box-shadow: 0 0 0 3px rgba(37,99,235,.12) !important; }
    .btn-primary-custom { background: linear-gradient(135deg, #1a499c, #2563eb); color: white; border: none; border-radius: 10px; padding: 11px 28px; font-weight: 600; transition: all .2s; }
    .btn-primary-custom:hover { opacity: .88; color: white; }
    .btn-danger-custom { background: linear-gradient(135deg, #dc2626, #ef4444); color: white; border: none; border-radius: 10px; padding: 11px 28px; font-weight: 600; transition: all .2s; }
    .btn-danger-custom:hover { opacity: .88; color: white; }
    .avatar-upload-label { cursor: pointer; }
    .avatar-upload-label:hover .profile-avatar, .avatar-upload-label:hover .avatar-placeholder { opacity: .8; }
    .stats-badge { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 16px; text-align: center; }
    .stats-badge .num { font-size: 1.5rem; font-weight: 700; color: #0369a1; }
    .stats-badge .lbl { font-size: .78rem; color: #64748b; margin-top: 2px; }
    .sub-premium { background: #fef3c7; border-color: #fde68a; }
    .sub-premium .num { color: #d97706; }
</style>

<div class="container-fluid py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-white mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success_password'))
        <div class="alert alert-info alert-dismissible fade show text-white mb-4" role="alert">
            <i class="fas fa-key me-2"></i> {{ session('success_password') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ===== KOLOM KIRI: Profil Card ===== --}}
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-wrap">
                        <form id="fotoForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <input type="hidden" name="no_telepon" value="{{ $user->no_telepon }}">
                            <input type="hidden" name="bio" value="{{ $user->bio }}">
                            <label class="avatar-upload-label" for="fotoInput" title="Klik untuk ubah foto profil">
                                @if($user->foto_profil && file_exists(public_path('img/profil/' . $user->foto_profil)))
                                    <img src="{{ asset('img/profil/' . $user->foto_profil) }}" alt="Foto Profil" class="profile-avatar" id="previewImg">
                                @else
                                    <div class="avatar-placeholder" id="previewPlaceholder">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </label>
                            <input type="file" id="fotoInput" name="foto_profil" accept="image/*" class="d-none"
                                onchange="previewFoto(this); document.getElementById('fotoForm').submit();">
                        </form>
                    </div>
                </div>

                <div class="profile-body text-center">
                    <div class="profile-name">{{ $user->name }}</div>
                    <span class="profile-role role-admin">
                        <i class="fas fa-shield-alt me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    <p class="text-muted small mt-2 mb-0">{{ $user->email }}</p>

                    {{-- Stats --}}
                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <div class="stats-badge {{ $user->subscription == 'premium' ? 'sub-premium' : '' }}">
                                <div class="num">{{ ucfirst($user->subscription) }}</div>
                                <div class="lbl">Subscription</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-badge">
                                <div class="num">{{ \Carbon\Carbon::parse($user->created_at)->format('Y') }}</div>
                                <div class="lbl">Bergabung Sejak</div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Cepat --}}
                    <div class="info-card text-start mt-4">
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-phone me-2 text-primary"></i>Telepon</span>
                            <span class="info-value">{{ $user->no_telepon ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-calendar me-2 text-primary"></i>Bergabung</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-info-circle me-2 text-primary"></i>Bio</span>
                            <span class="info-value">{{ $user->bio ?? '—' }}</span>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-3" style="font-size:.75rem;">
                        <i class="fas fa-camera me-1"></i> Klik foto untuk mengubah foto profil
                    </small>
                </div>
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Form Edit & Ganti Password ===== --}}
        <div class="col-lg-8">

            {{-- Form Edit Profil --}}
            <div class="profile-card mb-4 p-4">
                <div class="section-title">
                    <i class="fas fa-user-edit me-2 text-primary"></i> Edit Informasi Profil
                </div>

                @if($errors->any())
                    <div class="alert alert-danger text-white">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="Masukkan email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="Contoh: 0812-3456-7890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <input type="text" class="form-control bg-light" value="{{ ucfirst($user->role) }}" disabled>
                            <small class="text-muted">Role tidak dapat diubah.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bio / Tentang Saya</label>
                            <textarea name="bio" class="form-control" rows="3" placeholder="Ceritakan sedikit tentang Anda...">{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">Maksimal 500 karakter.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ubah Foto Profil</label>
                            <input type="file" name="foto_profil" class="form-control" accept="image/*"
                                onchange="previewFotoForm(this)">
                            <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Form Ganti Password --}}
            <div class="profile-card p-4">
                <div class="section-title">
                    <i class="fas fa-lock me-2 text-danger"></i> Ganti Password
                </div>

                @if($errors->has('password_lama'))
                    <div class="alert alert-danger text-white py-2">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first('password_lama') }}
                    </div>
                @endif

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_lama" id="passLama" class="form-control" placeholder="Masukkan password lama" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passLama', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_baru" id="passBaru" class="form-control" placeholder="Minimal 6 karakter" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passBaru', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_baru_confirmation" id="passKonfirmasi" class="form-control" placeholder="Ulangi password baru" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passKonfirmasi', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Password Strength --}}
                        <div class="col-12">
                            <div id="passwordStrength" class="d-none">
                                <small class="fw-semibold">Kekuatan Password:</small>
                                <div class="progress mt-1" style="height: 6px; border-radius: 4px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                </div>
                                <small id="strengthText" class="text-muted"></small>
                            </div>
                        </div>

                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-danger-custom">
                                <i class="fas fa-key me-2"></i> Ganti Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview foto profil saat dipilih dari card kiri
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var prev = document.getElementById('previewImg');
                var placeholder = document.getElementById('previewPlaceholder');
                if (prev) {
                    prev.src = e.target.result;
                } else if (placeholder) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('profile-avatar');
                    img.id = 'previewImg';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Preview foto dari form edit
    function previewFotoForm(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var prev = document.getElementById('previewImg');
                var placeholder = document.getElementById('previewPlaceholder');
                if (prev) { prev.src = e.target.result; }
                else if (placeholder) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('profile-avatar');
                    img.id = 'previewImg';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Toggle show/hide password
    function togglePass(id, btn) {
        var field = document.getElementById(id);
        var icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Password strength meter
    document.getElementById('passBaru').addEventListener('input', function() {
        var val = this.value;
        var wrap = document.getElementById('passwordStrength');
        var bar = document.getElementById('strengthBar');
        var txt = document.getElementById('strengthText');

        if (!val) { wrap.classList.add('d-none'); return; }
        wrap.classList.remove('d-none');

        var score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        var levels = [
            { pct: '20%', cls: 'bg-danger',  label: 'Sangat Lemah' },
            { pct: '40%', cls: 'bg-warning',  label: 'Lemah' },
            { pct: '60%', cls: 'bg-info',     label: 'Cukup' },
            { pct: '80%', cls: 'bg-primary',  label: 'Kuat' },
            { pct: '100%',cls: 'bg-success',  label: 'Sangat Kuat' },
        ];
        var lv = levels[Math.min(score - 1, 4)] || levels[0];
        bar.style.width = lv.pct;
        bar.className = 'progress-bar ' + lv.cls;
        txt.textContent = lv.label;
    });
</script>
@endsection
