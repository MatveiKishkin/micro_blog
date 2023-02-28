<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\BlogPostCreate as BlogPostBase;

class BlogCreatePost
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->create($args);
    }
}