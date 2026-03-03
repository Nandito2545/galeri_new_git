<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Tabel users
         * - Untuk login/register
         * - Role: admin/user
         * - Subscription: free/premium
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // wajib 'name' sesuai form Laravel
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->enum('subscription', ['free','premium'])->default('free');
            $table->timestamp('subscription_ends_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        /**
         * Tabel password_resets
         * Sesuai default Laravel
         */
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /**
         * Tabel sessions (jika pakai SESSION_DRIVER=database)
         */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /**
         * Tabel articles (opsional)
         * Bisa digunakan untuk artikel free/premium
         */
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('image')->nullable();
            $table->enum('status', ['free', 'premium'])->default('free'); // artikel premium/free
            $table->timestamps();
        });

        /**
         * Tabel pivot user_article
         * Jika ingin track artikel yang sudah dibeli user
         */
        Schema::create('user_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_article');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
};