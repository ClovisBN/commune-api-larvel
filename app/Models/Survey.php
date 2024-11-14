<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'title_survey', 'description_survey', 'content_survey', 'user_id', 'status_id'];

    protected $casts = [
        'content_survey' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(SurveyStatus::class, 'status_id');
    }    
}
