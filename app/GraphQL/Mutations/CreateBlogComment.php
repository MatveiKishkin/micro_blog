<?php

namespace App\GraphQL\Mutations;

use App\Base\BlogPost\Actions\CreateBlogPost as BlogPostBase;
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
        return true;
    }
}
