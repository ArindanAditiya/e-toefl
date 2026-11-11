<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'question_title_id' => 'required',
            'title' => 'required',
            'duration_type' => 'required',
            // 'duration' => 'required',
            'description' => 'required',
            'random_question' => 'required',
            'random_answer' => 'required',
            'show_answer' => 'required',
            'show_question_number_navigation' => 'required',
            'show_question_number' => 'required',
            'next_question_automatically' => 'required',
            'show_prev_next_button' => 'required',
            'type_option' => 'required',
            'price' => 'required',
            'total_tolerance' => 'required',
            // 'duration_section.*' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('exam.category_id'),
            'question_title_id' => __('exam.question_title_id'),
            'title' => __('exam.title'),
            'duration' => __('exam.duration'),
            'description' => __('exam.description'),
            'random_question' => __('exam.random_question'),
            'random_answer' => __('exam.random_answer'),
            'show_answer' => __('exam.show_answer'),
            'show_question_number_navigation' => __('exam.show_question_number_navigation'),
            'show_question_number' => __('exam.show_question_number'),
            'next_question_automatically' => __('exam.next_question_automatically'),
            'show_prev_next_button' => __('exam.show_prev_next_button'),
            'type_option' => __('exam.type_option'),
            'price' => __('exam.price'),
            'total_tolerance' => __('exam.total_tolerance'),
        ];
    }
}
