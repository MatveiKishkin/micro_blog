<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\BlogPost as BlogPostBase;

class BlogPostUpdate
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->update($args);
    }
}