<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterData\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [
                "id" => "ab1e771a-0037-40a4-be89-527224a6d4cc",
                "created_at" => "2022-09-16 18:59:06",
                "updated_at" => "2022-12-05 07:40:05",
                "deleted_at" => null,
                "name" => "TOEIC",
                "thumbnail" => "2305280618_toeic-thumbnail.png"
            ],
            [
                "id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
                "created_at" => "2022-09-16 18:59:12",
                "updated_at" => "2022-10-24 00:41:01",
                "deleted_at" => null,
                "name" => "TOEFL",
                "thumbnail" => "2305280658_toefl-thumbnail.png"
            ],
            // [
            //     "id" => "0f4348b6-10ed-4d98-ac73-bc9970dc8b73",
            //     "created_at" => "2022-09-16 18:59:13",
            //     "updated_at" => "2022-10-24 00:41:02",
            //     "deleted_at" => null,
            //     "name" => "IELTS",
            //     "thumbnail" => "2210240213_1 KEDINASAN.jpg",
            // ],
        ]);
    }
}
