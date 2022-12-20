<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_post_id',
        'name',
        'content',
        'status',
    ];
}
