<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Statistik (Card Atas)
        $totalRevenue = PaymentLog::whereIn('status', ['settlement', 'capture'])->sum('gross_amount');
        
        $thisMonthRevenue = PaymentLog::whereIn('status', ['settlement', 'capture'])
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('gross_amount');
            
        $pendingRevenue = PaymentLog::where('status', 'pending')->sum('gross_amount');
        $failedRevenue = PaymentLog::whereIn('status', ['expire', 'cancel', 'deny', 'failure'])->sum('gross_amount');

        // 2. Data Tabel Transaksi
        $query = PaymentLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'success') {
                $query->whereIn('status', ['settlement', 'capture']);
            } elseif ($request->status == 'failed') {
                $query->whereIn('status', ['expire', 'cancel', 'deny', 'failure']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->paginate(20);

        return view('admin.finance.index', compact(
            'transactions', 'totalRevenue', 'thisMonthRevenue', 'pendingRevenue', 'failedRevenue'
        ));
    }

    // Fungsi untuk mensinkronisasi semua transaksi 'pending' dengan Midtrans API
    public function syncPending()
    {
        $pendingLogs = PaymentLog::with('user')->where('status', 'pending')->get();
        $syncedCount = 0;
        
        if ($pendingLogs->isEmpty()) {
            return back()->with('info', 'Tidak ada transaksi pending yang perlu disinkronisasi.');
        }

        $serverKey = config('midtrans.server_key');
        $baseUrl = config('midtrans.is_production') ? 'https://api.midtrans.com/v2' : 'https://api.sandbox.midtrans.com/v2';
        
        foreach ($pendingLogs as $log) {
            try {
                $response = Http::withBasicAuth($serverKey, '')
                                ->get("{$baseUrl}/{$log->order_id}/status");
                
                if ($response->successful()) {
                    $result = $response->json();
                    $status = $result['transaction_status'] ?? $log->status;
                    
                    if ($status !== $log->status) {
                        $log->status = $status;
                        $log->payment_type = $result['payment_type'] ?? $log->payment_type;
                        $log->transaction_id = $result['transaction_id'] ?? $log->transaction_id;
                        $log->raw_response = $result;
                        
                        if (isset($result['va_numbers']) && count($result['va_numbers']) > 0) {
                            $log->bank = $result['va_numbers'][0]['bank'] ?? null;
                            $log->va_number = $result['va_numbers'][0]['va_number'] ?? null;
                        }

                        if ($status == 'settlement' || $status == 'capture') {
                            $log->paid_at = now();
                            
                            // Update user subscription jika belum diupdate
                            $user = $log->user;
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
                            $log->expired_at = now();
                        }
                        
                        $log->save();
                        $syncedCount++;
                    }
                }
            } catch (\Exception $e) {
                // Lewati jika terjadi error pada satu transaksi agar loop tidak berhenti
                continue;
            }
        }

        return back()->with('success', "Sinkronisasi selesai. Sebanyak $syncedCount transaksi telah diperbarui statusnya.");
    }
}
