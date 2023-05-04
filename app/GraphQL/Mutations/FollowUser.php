<?php

namespace App\GraphQL\Mutations;

use \App\Base\User\Actions\FollowUser as FollowUserBase;
use ProfilanceGroup\BackendSdk\Support\Response;

final class FollowUser
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = app(FollowUserBase::class)->follow($args['follows_id']);

        if (!is_numeric($user)) {
            return Response::error('', $user['operation_status']);
        }

        return Response::success('Вы успешно подписались на автора');
    }
}
