<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\BlogPost as BlogPostBase;

class BlogPostCreate
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->create($args);
    }
}