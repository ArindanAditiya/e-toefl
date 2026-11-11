<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ValueCategory extends Model
{
    use HasFactory,SoftDeletes,Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'assessment_type',
        'section'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\MasterData\Category::class)->withTrashed();
    }
}
