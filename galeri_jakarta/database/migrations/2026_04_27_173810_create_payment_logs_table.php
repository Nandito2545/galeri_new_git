<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('snap_token')->nullable();
            $table->enum('status', ['pending', 'settlement', 'capture', 'expire', 'cancel', 'deny', 'failure', 'refund'])->default('pending');
            $table->string('payment_type')->nullable();    // gopay, credit_card, bank_transfer, dll
            $table->bigInteger('gross_amount')->default(0);
            $table->string('va_number')->nullable();       // Virtual Account number
            $table->string('bank')->nullable();            // BCA, BNI, dll
            $table->string('fraud_status')->nullable();    // accept, challenge, deny
            $table->string('transaction_id')->nullable();  // Midtrans transaction_id
            $table->timestamp('paid_at')->nullable();      // Waktu settlement
            $table->timestamp('expired_at')->nullable();   // Waktu kadaluarsa VA
            $table->json('raw_response')->nullable();      // Full response dari Midtrans
            $table->text('notes')->nullable();             // Catatan admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
