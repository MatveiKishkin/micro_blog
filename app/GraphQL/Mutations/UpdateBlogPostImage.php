<?php

namespace App\GraphQL\Mutations;

use App\Models\BlogPost;

final class UpdateBlogPostImage
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
//        $file = $args['image'];
//        $path = $file->storePublicly('public/assets/images');

        $blog_post = BlogPost::find($args['blog_post_id']);
        $blog_post->addMedia($args['image'])->toMediaCollection('images');
//        $blog_post->update(['image' => $path]);

        return $blog_post;
    }
}
