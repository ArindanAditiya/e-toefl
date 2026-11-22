<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\MasterData\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Ramsey\Uuid\Uuid;
use App\Notifications\SendNewUserNotification;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $niceNames = [
            'province_id' => 'Provinsi'
        ];

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'address' => 'required',
            'phone_number' => 'required|numeric|unique:students',
            'gender' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ],
        [], // messages
        [
            'name' => __('user.name'),
            'province_id' => __('user.province_id'),
            'city_id' => __('user.city_id'),
            'district_id' => __('user.district_id'),
            'village_id' => __('user.village_id'),
            'address' => __('user.address'),
            'phone_number' => __('user.phone_number'),
            'gender' => __('user.gender'),
            'email' => __('user.email'),
            'password' => __('user.password'),

        ])->validate();

        $user = User::create([
            'id' => Uuid::uuid4()->getHex(),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'level' => 2,
        ]);

        Student::create([
            'id' => Uuid::uuid4()->getHex(),
            'user_id' => $user->id,
            'province_id' => $input['province_id'],
            'city_id' => $input['city_id'],
            'district_id' => $input['district_id'],
            'village_id' => $input['village_id'],
            'address' => $input['address'],
            'phone_number' => $input['phone_number'],
            'gender' => $input['gender'],
            'is_member' => 0,
        ]);
          
        $appUrl = rtrim(config('app.url') ?: env('APP_URL', ''), '/');
        $appName = "e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng";
        $activationUrl = $appUrl . "/user/" . $user->id . "/activation";

        sendBrevoNotification(
            $user->email,
            $user->name,
            "Aktivasi Akun e-TOEFL - Ma'had Aly Hasyim Asy'ari Tebuireng",
            [
                "Selamat datang di platform resmi Ujian <strong>e-TOEFL Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang</strong>.",
                "Akun Anda telah berhasil didaftarkan. Untuk mulai menggunakan akun dan mengikuti ujian prediksi TOEFL/TOEIC, silakan klik tombol aktivasi di bawah ini:"
            ],
            [
                'Nama Lengkap' => htmlspecialchars($user->name),
                'Email Terdaftar' => htmlspecialchars($user->email),
                'Nomor WhatsApp' => htmlspecialchars($user->student->phone_number),
            ],
            $activationUrl,
            "Aktifkan Akun Saya Sekarang",
            "Tautan aktivasi berlaku untuk memverifikasi keabsahan data peserta. Mohon jaga kerahasiaan akun Anda."
        );
        
        $admin = User::find('20b2a4122c614bb68e41b1a6f3f37780');
        if ($admin) {
            $admin->notify(new SendNewUserNotification($user));
        }

        return $user;
    }

    public function setAttributeNames(array $attributes)
    {
        $this->customAttributes = $attributes;

        return $this;
    }
}
