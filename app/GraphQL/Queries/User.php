<?php

namespace App\GraphQL\Queries;

use App\Models\User as UserModel;

final class User
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return UserModel::all();
    }
}
