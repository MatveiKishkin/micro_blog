<?php

namespace App\GraphQL\Mutations;

final class Logout
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $guard = \Auth::guard(config('sanctum.guard', 'web'));

        $user = $guard->user();
        $guard->logout();

        return $user;
    }
}
