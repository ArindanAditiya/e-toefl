<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Grade extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'answers' => 'json',
        'end_time' => 'datetime:Y-m-d H:i:s',
        'start_time' => 'datetime:Y-m-d H:i:s',
    ];

    // protected $appends = ['gradeTotal'];

    protected $fillable = [
        'id',
        'category_id',
        'exam_id',
        'user_id',
        'duration',
        'number',
        'section',
        'total_section',
        'start_time',
        'end_time',
        'total_correct',    
        'grade_old',
        'grade',
        'answers',
        'is_finished',
        'total_tolerance',
        'is_blocked'
    ];


    public function category()
    {
        return $this->belongsTo(\App\Models\MasterData\Category::class)->withTrashed();
    }

    public function exam()
    {
        return $this->belongsTo(\App\Models\Exam\Exam::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function gradeDetail()
    {
        return $this->hasMany(\App\Models\Exam\GradeDetail::class);
    }

    protected function grade(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }

    protected function gradeOld(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }
}
