<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MasterData\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class GoogleAuthController extends Controller
{
    /**
     * Redirect pengguna ke halaman otorisasi Google OAuth
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        // Jika kredensial belum diisi di .env, berikan notifikasi ramah tanpa fatal error
        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', 'Integrasi Google Sign-In sedang dalam penyiapan kredensial Google Client ID di .env. Silakan masuk menggunakan Email dan Password.');
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal menghubungi layanan Google: ' . $e->getMessage());
        }
    }

    /**
     * Menerima callback dari Google OAuth
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal memproses autentikasi Google. Silakan coba lagi.');
        }

        if (!$googleUser || empty($googleUser->getEmail())) {
            return redirect()->route('login')->with('error', 'Email dari akun Google tidak ditemukan.');
        }

        $email = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $name = $googleUser->getName() ?: 'Mahasantri';
        $avatar = $googleUser->getAvatar();

        // 1. Cari user berdasarkan google_id
        $user = User::where('google_id', $googleId)->first();

        // 2. Jika belum ada berdasarkan google_id, cari berdasarkan email
        if (!$user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                // Akun sudah ada dengan email sama, tautkan akun Google
                $user->update([
                    'google_id' => $googleId,
                    'avatar' => $user->avatar ?: $avatar,
                    'is_active' => 1,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ]);
            } else {
                // Registrasi akun baru secara instan
                $user = User::create([
                    'id' => Uuid::uuid4()->getHex(),
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'password' => Hash::make(Str::random(32)),
                    'level' => 2, // Mahasantri / Student
                    'is_active' => 1, // Email Google sudah terverifikasi otomatis
                    'email_verified_at' => now(),
                ]);

                // Buat profil student dengan default yang aman
                Student::create([
                    'id' => Uuid::uuid4()->getHex(),
                    'user_id' => $user->id,
                    'address' => 'Pondok Pesantren Tebuireng Jombang',
                    'gender' => 'M',
                    'is_member' => 0,
                ]);
            }
        } else {
            // Perbarui avatar jika ada pembaruan dari Google
            if ($avatar && $user->avatar !== $avatar) {
                $user->update(['avatar' => $avatar]);
            }
        }

        // Pastikan akun aktif
        if ($user->is_active == 0) {
            $user->update(['is_active' => 1]);
        }

        // Login pengguna
        Auth::login($user, true);

        // Catat aktivitas login terakhir
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        if ($user->level == 1) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/user/dashboard');
    }
}
