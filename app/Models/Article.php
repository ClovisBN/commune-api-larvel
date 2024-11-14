<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'title_article', 'description_article', 'content_article', 'user_id', 'status_id'];

    protected $casts = [
        'content_article' => 'array',
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
        return $this->belongsTo(ArticleStatus::class, 'status_id');
    }    
}
