<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\CreateBlogPost as BlogPostBase;

class CreateBlogPost
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->create($args);
    }
}