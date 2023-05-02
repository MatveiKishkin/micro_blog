<?php

namespace App\GraphQL\Queries;

final class Follows
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $current_user = \Auth::user()->getModel();

        return $current_user->follows()->get()->toArray();
    }
}
