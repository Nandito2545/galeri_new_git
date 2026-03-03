<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Register AJAX
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', // wajib sama dengan form
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'subscription' => 'free',
        ]);

        Session::put('pending_user', $user->id); // untuk payment nanti

        return response()->json(['success' => true]);
    }

    // Login AJAX
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // 1. Cek jika dia Admin
        if ($user->role === 'admin') {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.dashboard')
            ]);
        }

        // 2. Jika bukan admin, cek status langganan user biasa
        $redirectUrl = ($user->subscription === 'premium') 
            ? route('beranda') 
            : route('payment.page');

        return response()->json([
            'success' => true,
            'redirect' => $redirectUrl
        ]);
    }

    return response()->json(['error' => 'Email atau password salah']);
}
public function logout(Request $request)
{
    Auth::logout(); // logout user

    $request->session()->invalidate(); // hapus session
    $request->session()->regenerateToken(); // regenerate CSRF

    return redirect('/login'); // arahkan ke login
}
}