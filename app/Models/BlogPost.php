<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_posts';

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'h1',
        'content',
        'preview_text',
        'preview_image',
        'image',
    ];

    /**
     * Автор статьи.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author()
    {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
}
