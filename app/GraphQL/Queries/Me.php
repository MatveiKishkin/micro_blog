<?php

namespace App\GraphQL\Queries;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

final class Me
{
    /**
     *  Получение информации о текущем пользователе.
     *
     * @param null $_
     * @param array{} $args
     *
     * @return \App\Models\User
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard()->user();

        if (empty($user)) {
            throw new Error('Пользователь не авторизован.');
        }

        return $user;
    }
}
