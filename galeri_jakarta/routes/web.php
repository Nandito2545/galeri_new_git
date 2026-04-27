<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\PublikasiController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\GaleriFotoController;

// Halaman Utama (Timeline/Login/Register)
Route::get('/', function () {
    return view('auth.index');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
})->name('login.get');

// Proses Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================================
// VERIFIKASI EMAIL
// =============================================================
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/payment'); // Setelah verify, redirect ke payment
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Link verifikasi telah dikirim ulang!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// =============================================================
// Proteksi: Harus Login & Terverifikasi
// =============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // =============================================================
    // RUTE PEMBAYARAN
    // =============================================================
    Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');

    Route::get('/payment', function() {
        if (auth()->check() && auth()->user()->subscription === 'premium') {
            return redirect()->route('beranda');
        }
        return view('payment');
    })->name('payment.page');

    // Proteksi: Harus Premium
    Route::get('/beranda', function() {
        if (auth()->user()->subscription !== 'premium') {
            return redirect()->route('payment.page');
        }
        return view('beranda');
    })->name('beranda');

    // === PROFILE USER ===
    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/update', [App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');

    Route::get('/fitur/{slug}', function($slug) {
        if (auth()->user()->subscription !== 'premium') {
            return redirect()->route('payment.page');
        }

        $kategoriSlugMap = [
            'buku' => 'Buku',
            'kata' => 'Kata',
            'kota' => 'Kota',
            'inspirasi' => 'Inspirasi',
            'gairah' => 'Gairah',
            'cerita' => 'Cerita',
        ];

        $group1 = ['buku', 'kata', 'kota'];
        $group2 = ['inspirasi', 'gairah', 'cerita'];

        if (in_array($slug, $group1)) {
            $kategoriName = $kategoriSlugMap[$slug];
            $artikels = \App\Models\Artikel::where('kategori', $kategoriName)->orderBy('created_at', 'desc')->get();
            return view('pages.fitur_esai', ['kategori' => $kategoriName, 'artikels' => $artikels]);
        } elseif (in_array($slug, $group2)) {
            $kategoriName = $kategoriSlugMap[$slug];
            $artikels = \App\Models\Artikel::where('kategori', $kategoriName)->orderBy('created_at', 'desc')->get();
            return view('pages.fitur_artikel', ['kategori' => $kategoriName, 'artikels' => $artikels]);
        }

        // Fallback untuk kategori Slide 2 jika belum dibuat view dinamisnya
        if (view()->exists("pages.fitur_$slug")) {
            return view("pages.fitur_$slug");
        }
        
        abort(404);
    })->name('fitur.detail');
});

// =============================================================
// Rute khusus Admin
// =============================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // === ARTIKEL ===
    Route::get('/admin/artikel', [ArtikelController::class, 'index'])->name('admin.artikel.index');
    Route::get('/admin/artikel/tambah', [ArtikelController::class, 'create'])->name('admin.artikel.create');
    Route::post('/admin/artikel/simpan', [ArtikelController::class, 'store'])->name('admin.artikel.store');
    Route::get('/admin/artikel/{id}/edit', [ArtikelController::class, 'edit'])->name('admin.artikel.edit');
    Route::put('/admin/artikel/{id}', [ArtikelController::class, 'update'])->name('admin.artikel.update');
    Route::delete('/admin/artikel/{id}', [ArtikelController::class, 'destroy'])->name('admin.artikel.destroy');
    Route::post('/admin/artikel/bulk', [ArtikelController::class, 'bulkAction'])->name('admin.artikel.bulk');
    Route::post('/admin/artikel/{id}/toggle-status', [ArtikelController::class, 'toggleStatus'])->name('admin.artikel.toggle');

    // === PUBLIKASI ===
    Route::get('/admin/publications', [PublikasiController::class, 'index'])->name('admin.publikasi.index');
    Route::get('/admin/publications/tambah', [PublikasiController::class, 'create'])->name('admin.publikasi.create');
    Route::post('/admin/publications/simpan', [PublikasiController::class, 'store'])->name('admin.publikasi.store');
    Route::get('/admin/publications/{id}/edit', [PublikasiController::class, 'edit'])->name('admin.publikasi.edit');
    Route::put('/admin/publications/{id}', [PublikasiController::class, 'update'])->name('admin.publikasi.update');
    Route::delete('/admin/publications/{id}', [PublikasiController::class, 'destroy'])->name('admin.publikasi.destroy');
    Route::post('/admin/publications/bulk', [PublikasiController::class, 'bulkAction'])->name('admin.publikasi.bulk');
    Route::post('/admin/publications/{id}/toggle-status', [PublikasiController::class, 'toggleStatus'])->name('admin.publikasi.toggle');

    // === GALERI FOLDER ===
    Route::get('/admin/galeri', [GaleriController::class, 'index'])->name('admin.galeri.index');
    Route::get('/admin/galeri/tambah', [GaleriController::class, 'create'])->name('admin.galeri.create');
    Route::post('/admin/galeri/simpan', [GaleriController::class, 'store'])->name('admin.galeri.store');
    Route::get('/admin/galeri/{id}/edit', [GaleriController::class, 'edit'])->name('admin.galeri.edit');
    Route::put('/admin/galeri/{id}', [GaleriController::class, 'update'])->name('admin.galeri.update');
    Route::delete('/admin/galeri/{id}', [GaleriController::class, 'destroy'])->name('admin.galeri.destroy');
    Route::post('/admin/galeri/{id}/toggle-status', [GaleriController::class, 'toggleStatus'])->name('admin.galeri.toggle');

    // === GALERI ISI (FOTO) ===
    Route::get('/admin/galeri/isi/{id}', [GaleriFotoController::class, 'index'])->name('admin.galeri.isi');
    Route::post('/admin/galeri/isi/{id}/store', [GaleriFotoController::class, 'store'])->name('admin.galeri.isi.store');
    Route::delete('/admin/galeri/isi/foto/{id}', [GaleriFotoController::class, 'destroy'])->name('admin.galeri.isi.destroy');

    // === PESAN ===
    Route::get('/admin/pesan', [PesanController::class, 'index'])->name('admin.pesan.index');
    Route::get('/admin/pesan/detail/{id}', [PesanController::class, 'show'])->name('admin.pesan.show');
    Route::delete('/admin/pesan/{id}', [PesanController::class, 'destroy'])->name('admin.pesan.destroy');

    // === PROFIL ADMIN ===
    Route::get('/admin/profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile');
    Route::post('/admin/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // === PUSTAKA (BUKU) ===
    Route::get('/admin/pustaka', [App\Http\Controllers\Admin\PustakaController::class, 'index'])->name('admin.pustaka.index');
    Route::get('/admin/pustaka/tambah', [App\Http\Controllers\Admin\PustakaController::class, 'create'])->name('admin.pustaka.create');
    Route::post('/admin/pustaka', [App\Http\Controllers\Admin\PustakaController::class, 'store'])->name('admin.pustaka.store');
    Route::get('/admin/pustaka/{id}/edit', [App\Http\Controllers\Admin\PustakaController::class, 'edit'])->name('admin.pustaka.edit');
    Route::put('/admin/pustaka/{id}', [App\Http\Controllers\Admin\PustakaController::class, 'update'])->name('admin.pustaka.update');
    Route::delete('/admin/pustaka/{id}', [App\Http\Controllers\Admin\PustakaController::class, 'destroy'])->name('admin.pustaka.destroy');
    Route::post('/admin/pustaka/{id}/toggle', [App\Http\Controllers\Admin\PustakaController::class, 'toggleStatus'])->name('admin.pustaka.toggle');
    Route::post('/admin/pustaka/bulk', [App\Http\Controllers\Admin\PustakaController::class, 'bulkAction'])->name('admin.pustaka.bulk');

    // === KELOLA USER & PEMBAYARAN ===
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users/{id}/check-status', [App\Http\Controllers\Admin\UserController::class, 'checkStatus'])->name('admin.users.check-status');
    Route::post('/admin/users/{id}/create-payment', [App\Http\Controllers\Admin\UserController::class, 'createPayment'])->name('admin.users.create-payment');
    Route::post('/admin/users/{id}/update-subscription', [App\Http\Controllers\Admin\UserController::class, 'updateSubscription'])->name('admin.users.update-subscription');
    Route::delete('/admin/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // === KELOLA KEUANGAN ===
    Route::get('/admin/finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('admin.finance.index');
    Route::post('/admin/finance/sync-pending', [App\Http\Controllers\Admin\FinanceController::class, 'syncPending'])->name('admin.finance.sync');
});

// === MIDTRANS WEBHOOK ===
Route::post('/webhook/midtrans', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook.midtrans');