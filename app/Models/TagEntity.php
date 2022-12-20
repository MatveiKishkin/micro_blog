<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagEntity extends Model
{
    use HasFactory;

    protected $table = 'tags_entities';

    protected $fillable = [
        'blog_tags_id',
        'entity',
    ];
}
