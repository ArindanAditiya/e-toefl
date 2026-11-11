<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GradeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'exam_id',
        'user_id',
        'grade_id',
        'value_category_id',
        'total_correct',
        'total_wrong',
        'grade',
    ];

    public function valueCategory()
    {
        return $this->belongsTo(\App\Models\Lesson\ValueCategory::class);
    }

    protected function grade(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value),
        );
    }
}
