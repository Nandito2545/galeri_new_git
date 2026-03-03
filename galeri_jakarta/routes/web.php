<?php
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

// Halaman Utama (Timeline/Login/Register)
Route::get('/', function () {
    return view('auth.index');
})->name('home');

// Redirect jika akses /login manual
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login.get');

// Proses Auth
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Proteksi: Harus Login
Route::middleware(['auth'])->group(function () {
    
    // Halaman Pembayaran (Khusus user yang belum premium)
    Route::get('/payment', function() {
        if (auth()->user()->subscription === 'premium') {
            return redirect()->route('beranda');
        }
        return view('payment');
    })->name('payment.page');

    // Proses Pembayaran Midtrans
    Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');

    // Proteksi: Harus Premium (Menggunakan Logic Langsung di Rute)
    Route::get('/beranda', function() {
        if (auth()->user()->subscription !== 'premium') {
            return redirect()->route('payment.page');
        }
        return view('beranda');
    })->name('beranda');

    // Rute Otomatis untuk Detail Fitur (Hanya untuk Premium)
    Route::get('/fitur/{slug}', function($slug) {
        if (auth()->user()->subscription !== 'premium') {
            return redirect()->route('payment.page');
        }
        
        if (view()->exists("pages.fitur_$slug")) {
            return view("pages.fitur_$slug");
        }
        abort(404);
    })->name('fitur.detail');
});

// Rute khusus Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function() {
        // Proteksi tambahan: Pastikan role-nya admin
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak');
        }
        return view('admin.index'); // Mengarah ke resources/views/admin/index.blade.php
    })->name('admin.dashboard');
});

// Ubah rute dashboard admin di dalam grup middleware Anda
// Rute khusus Admin
Route::middleware(['auth'])->group(function () {
    
    // ... rute dashboard admin yang sebelumnya sudah ada ...
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // --- DAFTAR RUTE MENU ADMIN (SIAPKAN UNTUK NANTI) ---
    
    // Rute Artikel
    Route::get('/admin/artikel', function() { return "Halaman Data Artikel"; });
    Route::get('/admin/artikel/tambah', function() { return "Halaman Tambah Artikel"; });
    Route::get('/admin/artikel/detail/{id}', function($id) { return "Detail Artikel ID: " . $id; });

    // Rute Galeri
    Route::get('/admin/galeri', function() { return "Halaman Data Galeri"; });
    Route::get('/admin/galeri/tambah', function() { return "Halaman Tambah Galeri"; });
    Route::get('/admin/galeri/isi/{id}', function($id) { return "Isi Galeri ID: " . $id; });

    // Rute Pesan
    Route::get('/admin/pesan', function() { return "Halaman Pesan"; });
    Route::get('/admin/pesan/detail/{id}', function($id) { return "Detail Pesan ID: " . $id; });
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])
    ->name('logout');
    
}); 