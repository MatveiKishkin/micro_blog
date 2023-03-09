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
        $file = $args['image'];
        $path = $file->storePublicly('public/assets/images');

        $blog_post = BlogPost::find($args['id']);
        $blog_post->update(['image' => $path]);

        return $blog_post;
    }
}
