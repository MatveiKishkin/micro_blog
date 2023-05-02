<?php

namespace App\GraphQL\Queries;

final class Followers
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $current_user = \Auth::user()->getModel();

        return $current_user->followers()->get()->toArray();
    }

}
