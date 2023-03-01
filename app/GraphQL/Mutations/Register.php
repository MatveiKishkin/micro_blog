<?php

namespace App\GraphQL\Mutations;

use App\Models\User as UserModel;
use GraphQL\Error\Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class Register
{
    /**
     * Регистрация пользователя.
     *
     * @param null $_
     * @param array{} $args
     * @return \App\Models\User
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            throw new Error('Ошибка регистрации пользователя: '.json_encode($validator->errors()));
        }

        $user = new UserModel();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = Hash::make($args['password']);
        $user->save();

        return $user;
    }
}
