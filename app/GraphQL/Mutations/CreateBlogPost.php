<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\CreateBlogPost as BlogPostBase;
use ProfilanceGroup\BackendSdk\Support\Response;

class CreateBlogPost
{
    /**
     * @param null  $_
     * @param array{} $args
     */
    public function __invoke($_, array $args)
    {
        $blog_post = app(BlogPostBase::class)->create($args);

        return Response::success(null, [
            'blog_post' => $blog_post,
        ]);
    }
}