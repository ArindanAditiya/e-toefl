<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class QuestionTitle extends Model
{
    use HasFactory, SoftDeletes,Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'total_choices',
        'total_section',
        'add_value_category',
        'assessment_type',
        'show_answer',
        'minimum_grade',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\MasterData\Category::class)->withTrashed();
    }

    public function question()
    {
        return $this->hasMany(\App\Models\Lesson\Question::class)->withTrashed();

    }
}
