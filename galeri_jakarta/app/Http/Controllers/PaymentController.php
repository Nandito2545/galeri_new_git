<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        try {
            // 1. Dapatkan user (cek auth)
            $user = auth()->user(); 

            // Jika user benar-benar tidak ditemukan, tolak pembayaran
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => 'Sesi pengguna tidak ditemukan, silakan reload dan daftar ulang.'
                ], 400);
            }

            // 2. Set Config Midtrans (Sandbox)
            Config::$serverKey    = config('midtrans.server_key');
            Config::$clientKey    = config('midtrans.client_key');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $orderId = "ORDER-" . time() . "-" . $user->id;
            $amount = 49000;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $user->nama ?? $user->name ?? 'Guest', 
                    'email' => $user->email ?? 'guest@email.com',
                ],
            ];

            // 3. Minta Token
            $snapToken = Snap::getSnapToken($params);
            
            // 4. Log pembayaran ke DB
            \App\Models\PaymentLog::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'gross_amount' => $amount,
                'status' => 'pending',
                'payment_type' => null, // akan diisi via webhook
            ]);
            
            return response()->json([
                'snapToken' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Midtrans Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        $user = auth()->user();
        
        if ($user) {
            $user->subscription = 'premium';
            if ($user->subscription_ends_at && \Carbon\Carbon::parse($user->subscription_ends_at)->isFuture()) {
                $user->subscription_ends_at = \Carbon\Carbon::parse($user->subscription_ends_at)->addYear();
            } else {
                $user->subscription_ends_at = now()->addYear();
            }
            $user->save();

            Session::put('user_id', $user->id);
            Session::put('nama', $user->nama ?? $user->name);
            Session::put('role', $user->role);
        }

        return redirect()->route('beranda')->with('success', 'Pembayaran berhasil!');
    }

    // WEBHOOK: Midtrans akan hit URL ini saat ada perubahan status
    public function webhook(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        
        // 1. Validasi HMAC SHA512 Signature Key (SANGAT PENTING!)
        $signatureKey = hash('sha512', 
            $request->order_id . $request->status_code . $request->gross_amount . $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 2. Cari transaksi di database
        $paymentLog = \App\Models\PaymentLog::where('order_id', $request->order_id)->first();
        if (!$paymentLog) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Update status log
        $status = $request->transaction_status;
        $paymentLog->status = $status;
        $paymentLog->payment_type = $request->payment_type;
        $paymentLog->transaction_id = $request->transaction_id;
        $paymentLog->raw_response = $request->all();

        // 4. Update Bank/VA info jika ada
        if (isset($request->va_numbers) && count($request->va_numbers) > 0) {
            $paymentLog->bank = $request->va_numbers[0]['bank'];
            $paymentLog->va_number = $request->va_numbers[0]['va_number'];
        }

        // 5. Action berdasarkan status
        if ($status == 'settlement' || $status == 'capture') {
            $paymentLog->paid_at = now();
            
            // Activate User Subscription
            $user = $paymentLog->user;
            if ($user) {
                $user->subscription = 'premium';
                if ($user->subscription_ends_at && \Carbon\Carbon::parse($user->subscription_ends_at)->isFuture()) {
                    $user->subscription_ends_at = \Carbon\Carbon::parse($user->subscription_ends_at)->addYear();
                } else {
                    $user->subscription_ends_at = now()->addYear();
                }
                $user->save();
            }
        } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
            $paymentLog->expired_at = now();
        }

        $paymentLog->save();

        return response()->json(['message' => 'Webhook received successfully']);
    }
}