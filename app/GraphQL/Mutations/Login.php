<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nuwave\Lighthouse\Exceptions\ValidationException;

final class Login
{
    /**
     * Авторизация пользователя.
     *
     * @param null $_
     * @param array{} $args
     * @return array
     * @throws ValidationException
     */
    public function __invoke($_, array $args)
    {
        $user = User::where('email', $args['email'])->first();

        if (! $user || !\Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверно указаны данные.'],
            ]);
        }

        return [
            'token' => $user->createToken('login')->plainTextToken,
        ];

//        $guard = Auth::guard(\Arr::first(config('sanctum.guard')));
////        $guard = Auth::guard();
//
//        if (!$guard->attempt($args)) {
//            throw new Error('Переданы неверные параметры.');
//        }
//
//        /** @var \App\Models\User $user */
//        $user = $guard->user();
//
//        return $user;
    }
}
