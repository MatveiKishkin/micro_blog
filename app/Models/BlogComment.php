<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogComment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_post_id',
        'name',
        'content',
        'status',
    ];

    protected $appends = ['image'];

    /**
     * Получение url основного изображения.
     *
     * @return string
     */
    public function getImageAttribute() {

        if (!empty($this->is_deleted)) {
            return null;
        }

        $image = $this->getFirstMediaUrl('images');

        return $image ?? null;
    }
}
