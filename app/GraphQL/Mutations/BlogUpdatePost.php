<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\BlogPostUpdate as BlogPostBase;

class BlogUpdatePost
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->update($args);
    }
}