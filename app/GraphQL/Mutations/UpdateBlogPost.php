<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\UpdateBlogPost as BlogPostBase;

class UpdateBlogPost
{
    public function __invoke($root, array $args)
    {
        return app(BlogPostBase::class)->update($args);
    }
}