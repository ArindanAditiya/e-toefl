<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class DetailValueCategory extends Model
{
    use HasFactory, SoftDeletes, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'value_category_id',
        'min_grade',
        'max_grade',
        'final_grade',
    ];

    public function valueCategory()
    {
        return $this->belongsTo(\App\Models\Lesson\ValueCategory::class);
    }
}
