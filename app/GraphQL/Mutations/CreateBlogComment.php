<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogComment\Actions\CreateBlogComment as BlogCommentBase;
use ProfilanceGroup\BackendSdk\Exceptions\OperationError;
use ProfilanceGroup\BackendSdk\Support\Response;

final class CreateBlogComment
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $blog_comment = app(BlogCommentBase::class)->create($args);

        return Response::success(null, [
            'blog_comment' => $blog_comment,
        ]);
    }
}
