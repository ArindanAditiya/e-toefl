<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Auth;

class Exam extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'question_title_id',
        'title',
        'price',
        'duration',
        'duration_type',
        'description',
        'random_question',
        'random_answer',
        'show_answer',
        'show_question_number_navigation',
        'show_question_number',
        'next_question_automatically',
        'show_prev_next_button',
        'type_option',
        'total_tolerance',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\MasterData\Category::class)->withTrashed();
    }

    public function questionTitle()
    {
        return $this->belongsTo(\App\Models\Lesson\QuestionTitle::class)->withTrashed();
    }

    public function grade()
    {
        return $this->hasMany(\App\Models\Exam\Grade::class);
    }

    public function gradeFinished()
    {
        return $this->hasMany(\App\Models\Exam\Grade::class)->where('is_finished', 1);
    }

    public function transaction()
    {
        return $this->hasMany(\App\Models\Transaction\Transaction::class)->where(['transaction_status' => 'done', 'user_id' => Auth::user()->id]);
    }

}
