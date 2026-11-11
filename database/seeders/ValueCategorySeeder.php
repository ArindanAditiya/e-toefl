<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lesson\ValueCategory;

class ValueCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ValueCategory::insert([
            [
                "id" => "4519447b-6cd1-4d88-b072-5068662be0b0",
                "created_at" => "2023-05-24 04:58:23",
                "updated_at" => "2023-05-24 04:58:23",
                "deleted_at" => null,
                "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
                "name" => "Listening Comprehension",
                "assessment_type" => 2,
                "section" => 1,
            ],
            [
                "id" => "89e3775f-e252-4f78-9046-9dc1b9f61bba",
                "created_at" => "2023-05-24 04:58:55",
                "updated_at" => "2023-05-24 04:58:55",
                "deleted_at" => null,
                "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
                "name" => "Structure and Written Expression",
                "assessment_type" => 2,
                "section" => 2,
            ],
            [
                "id" => "03598a55-7910-48b1-8adf-ccf2e33b332e",
                "created_at" => "2023-05-24 04:59:12",
                "updated_at" => "2023-05-24 04:59:12",
                "deleted_at" => null,
                "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
                "name" => "Reading Comprehension",
                "assessment_type" => 2,
                "section" => 3,
            ],
            [
                "id" => "96ffbadf-a3b5-4353-b9ce-3e3a1ad44aaf",
                "created_at" => "2023-05-28 04:58:48",
                "updated_at" => "2023-05-28 04:58:48",
                "deleted_at" => null,
                "category_id" => "ab1e771a-0037-40a4-be89-527224a6d4cc",
                "name" => "Listening Comprehension",
                "assessment_type" => 2,
                "section" => 1,
            ],
            [
                "id" => "3b7af3ee-eadd-4e83-8804-a4b5293b2822",
                "created_at" => "2023-05-28 04:58:58",
                "updated_at" => "2023-05-28 04:59:03",
                "deleted_at" => null,
                "category_id" => "ab1e771a-0037-40a4-be89-527224a6d4cc",
                "name" => "Reading Comprehension",
                "assessment_type" => 2,
                "section" => 2,
            ],
        ]);
    }
}
