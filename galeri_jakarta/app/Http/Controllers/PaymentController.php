<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function pay(Request $request)
    {
        $user_id = Session::get('pending_user');
        $user = User::findOrFail($user_id);

        $order_id = "ORDER-" . time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 49000,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken' => $snapToken
        ]);
    }

    // Setelah pembayaran berhasil
    public function success(Request $request)
    {
        $user_id = Session::get('pending_user');
        $user = User::findOrFail($user_id);

        // Update user menjadi premium + set waktu berlangganan 1 tahun
        $user->subscription = 'premium';
        $user->subscription_ends_at = now()->addYear();
        $user->save();

        // Login otomatis setelah bayar
        Session::put('user_id', $user->id);
        Session::put('nama', $user->name);
        Session::put('role', $user->role);

        // Hapus session pending
        Session::forget('pending_user');

        return redirect()->route('home')->with('success', 'Pembayaran berhasil, akun premium aktif!');
    }
    public function createSnapToken()
{
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . uniqid(),
            'gross_amount' => 49000,
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name ?? 'Guest',
            'email' => auth()->user()->email ?? 'guest@email.com',
        ]
    ];

    $snapToken = Snap::getSnapToken($params);

    return view('payment', compact('snapToken'));
}
}