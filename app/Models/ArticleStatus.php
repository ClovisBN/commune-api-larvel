<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleStatus extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
