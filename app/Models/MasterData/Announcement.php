<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory,SoftDeletes, Uuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $dates = ['created_at'];

    protected $fillable = [
        'id',
        'title',
        'description',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getDescriptionAttribute($value)
    {
        $baseUrl = rtrim(config('app.url') ?: env('APP_URL', ''), '/');
        return str_replace("/storage/upload_files/questions", $baseUrl."/storage/upload_files/questions", $value);
    }
}
