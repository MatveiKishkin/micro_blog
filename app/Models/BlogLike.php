<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogLike extends Model
{
    use HasFactory;

    protected $table = 'blog_likes';

    protected $fillable = [
        'blog_post_id',
        'user_id',
    ];
}
