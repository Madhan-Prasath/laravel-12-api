<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $fillable = [
        'meta_title',
        'post_id',
        'meta_description',
        'meta_keywords',
    ];
}
