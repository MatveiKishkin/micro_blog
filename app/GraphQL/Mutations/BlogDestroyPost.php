<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\BlogPostDestroy as BlogPostBase;

class BlogDestroyPost
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->destroy($args['id']);
    }
}