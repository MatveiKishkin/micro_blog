<?php

namespace App\GraphQL\Mutations;

final class CreateUser
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
