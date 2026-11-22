<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterData\Student;
use App\Http\Requests\User\ForgotPasswordRequest;

class ActivationController extends Controller
{
    public function activation($userId)
    {
        $user = User::find($userId);
        if(!$user) {
            return redirect('login')->with('error', 'Akun tersebut tidak terdaftar dalam sistem.');
        }

        return inertia('Auth/Activation', [
            'user' => $user,
        ]);
    }

    public function actived($userId)
    {
        $user = User::find($userId);
        if(!$user) {
            return redirect('login')->with('error', 'Akun tersebut tidak terdaftar dalam sistem.');
        }

        if($user->is_active == 1) {
            return redirect('login')->with('success', 'Akun '.$user->email.' sudah aktif, silakan login.');
        }

        $user->update(['is_active' => 1]);
        return redirect('login')->with('success', 'Akun dengan email '.$user->email.' telah diaktifkan, silakan login.');
    }

    public function forgotPassword()
    {
        return inertia('Auth/ForgotPassword');
    }

    public function storeForgotPassword(ForgotPasswordRequest $request)
    {
        $student = Student::where('phone_number', $request->phone_number)->first();   
        if($student) {
            $user = User::find($student->user_id);

            if($user->is_active == 0) {
                return redirect()->back()->with('error', 'Untuk melakukan reset password, akun anda harus aktif terlebih dahulu, silakan hubungi admin untuk mendapatkan link aktivasi.');
            }
            
            $namePassword = explode(" ", $student->user->name);
            $newPassword = strtolower($namePassword[0]).mt_rand(10000, 99999);

            sendBrevoNotification(
                $user->email,
                $user->name,
                "Permintaan Perubahan Password - e-TOEFL Ma'had Aly Tebuireng",
                [
                    "Kami menerima permintaan pengaturan ulang (reset) kata sandi untuk akun e-TOEFL Anda.",
                    "Kata sandi baru telah digenerate oleh sistem secara otomatis. Silakan gunakan kredensial berikut untuk masuk ke platform:"
                ],
                [
                    'Nama' => htmlspecialchars($user->name),
                    'Email Akun' => htmlspecialchars($user->email),
                    'Kata Sandi Baru' => '<code style="background: #e2ede4; padding: 4px 8px; border-radius: 4px; font-weight: bold; color: #0A3D24;">' . htmlspecialchars($newPassword) . '</code>',
                ],
                url('/login'),
                "Masuk ke Akun Saya",
                "Mohon perhatikan penulisan huruf besar dan kecil saat login. Demi keamanan, simpan kata sandi ini dengan baik."
            );

            $user->update([
                'password' => bcrypt($newPassword)
            ]);
            
            return redirect()->route('login')->with('success', 'Kami telah mengirimkan informasi perubahan password ke email <b>'.$user->email.'</b> (dan WhatsApp jika aktif), silakan periksa kotak masuk Anda.');

        } else {
            return redirect()->back()->with('error', 'Nomor <b>'.$request->phone_number. '</b> tidak ditemukan, silakan klik link dibawah button kirim untuk menghubungi admin.');
        }
    }
}
