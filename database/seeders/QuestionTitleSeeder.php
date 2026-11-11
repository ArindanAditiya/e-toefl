<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lesson\QuestionTitle;

class QuestionTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionTitle::insert([
            [
                "id" => "7c050e8e-20dd-4538-8d51-2eb51ff303c9",
                "created_at" => "2023-05-24 15:45:13",
                "updated_at" => "2023-05-24 15:47:22",
                "deleted_at" => null,
                "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
                "name" => "Try Out TOEFL 1",
                "total_choices" => 4,
                "total_section" => 3,
                "add_value_category" => 1,
                "assessment_type" => 1,
                "show_answer" => 0,
                "minimum_grade" => 0
            ],
            // [
            //     "id" => "0eb0d8a1-ee2c-42b9-89b4-cc43d3161276",
            //     "created_at" => "2023-05-24 15:46:41",
            //     "updated_at" => "2023-05-24 15:46:51",
            //     "deleted_at" => null,
            //     "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
            //     "name" => "Try Out TOEFL 2",
            //     "total_choices" => 4,
            //     "total_section" => 3,
            //     "add_value_category" => 1,
            //     "assessment_type" => 1,
            //     "show_answer" => 0,
            //     "minimum_grade" => 0
            // ],
            // [
            //     "id" => "732b7673-8db5-4f52-b1cc-59186ba931b0",
            //     "created_at" => "2023-05-24 15:47:36",
            //     "updated_at" => "2023-05-24 15:47:36",
            //     "deleted_at" => null,
            //     "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
            //     "name" => "Try Out TOEFL 3",
            //     "total_choices" => 4,
            //     "total_section" => 3,
            //     "add_value_category" => 1,
            //     "assessment_type" => 1,
            //     "show_answer" => 0,
            //     "minimum_grade" => null
            // ],
            // [
            //     "id" => "bf0fdb67-67ce-4b6b-a0e3-1ceccb18d10a",
            //     "created_at" => "2023-05-24 15:47:50",
            //     "updated_at" => "2023-05-24 15:47:50",
            //     "deleted_at" => null,
            //     "category_id" => "f968e7a3-74ed-4090-b9bc-99e7c36f2a69",
            //     "name" => "Try Out TOEFL 4",
            //     "total_choices" => 4,
            //     "total_section" => 3,
            //     "add_value_category" => 1,
            //     "assessment_type" => 1,
            //     "show_answer" => 0,
            //     "minimum_grade" => null
            // ]
        ]);
    }
}
