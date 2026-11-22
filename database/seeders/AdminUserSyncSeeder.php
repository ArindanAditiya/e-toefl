<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Akun Admin Utama Resmi Ma'had Aly
        $admin = User::find('20b2a4122c614bb68e41b1a6f3f37780');
        if ($admin) {
            $admin->update([
                'name' => "Administrator e-TOEFL Ma'had Aly",
                'email' => 'admin@mahatbi.or.id',
                'password' => Hash::make('AdminTebuireng#2026'),
                'level' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        } else {
            User::create([
                'id' => '20b2a4122c614bb68e41b1a6f3f37780',
                'name' => "Administrator e-TOEFL Ma'had Aly",
                'email' => 'admin@mahatbi.or.id',
                'password' => Hash::make('AdminTebuireng#2026'),
                'level' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        }

        // 2. Akun Admin Sekunder / Developer
        $legacyAdmin = User::where('email', 'administrator@myenglish.com')->first();
        if (!$legacyAdmin) {
            User::create([
                'id' => '20b2a4122c614bb68e41b1a6f3f37781',
                'name' => 'Admin Developer',
                'email' => 'administrator@myenglish.com',
                'password' => Hash::make('AdminTebuireng#2026'),
                'level' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        } else {
            $legacyAdmin->update([
                'name' => 'Admin Developer',
                'password' => Hash::make('AdminTebuireng#2026'),
                'level' => 1,
                'is_active' => 1,
                'email_verified_at' => now(),
            ]);
        }
    }
}
