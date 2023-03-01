<?php

namespace App\GraphQL\Mutations;

use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;

final class Login
{
    /**
     * Авторизация пользователя.
     *
     * @param null $_
     * @param array{} $args
     * @return \App\Models\User
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
//        $guard = Auth::guard(Arr::first(config('sanctum.guard')));
        $guard = Auth::guard();

        if (!$guard->attempt($args)) {
            throw new Error('Переданы неверные параметры.');
        }

        /** @var \App\Models\User $user */
        $user = $guard->user();

        return $user;
    }
}
