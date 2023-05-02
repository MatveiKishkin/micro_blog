<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User as UserModel;
use App\Models\Subscriber as SubscriberModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BlogPost extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'blog_posts';

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'content',
    ];

    protected $appends = ['image'];

    /**
     * Автор статьи.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

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
