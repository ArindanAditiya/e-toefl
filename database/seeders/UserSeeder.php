<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                "id" => "20b2a4122c614bb68e41b1a6f3f37780",
                "name" => "Admin MyEnglish",
                "email" => "administrator@myenglish.com",
                "email_verified_at" => null,
                "password" => bcrypt('77422424'),
                "level" => 1,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83ccca",
                "name" => "Muhammad Iqbal Ramadhan",
                "email" => "iqbal@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('77422424'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccb",
                "name" => "Rifki Adha Darmawan",
                "email" => "rifki@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('Bandung2023'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccc",
                "name" => "Syahrul",
                "email" => "syahrul@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('Bandung2023'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccd",
                "name" => "User Testing 1",
                "email" => "usertesting1@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83ccce",
                "name" => "User Testing 2",
                "email" => "usertesting2@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
            [
                "id" => "62bcbaec-82a9-44f5-985b-e5abcc83cccf",
                "name" => "User Testing 3",
                "email" => "usertesting3@mail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "level" => 2,
                "is_active" => 1,
                "created_at" => "2022-10-02 00:58:31",
                "updated_at" => "2022-10-02 00:58:31"
            ],
        ]);
    }
}
