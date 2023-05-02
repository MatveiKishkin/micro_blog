<?php

namespace App\GraphQL\Mutations;

use App\Models\User as UserModel;
use GraphQL\Error\Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Exceptions\ValidationException;

final class Register
{
    /**
     * Регистрация пользователя.
     *
     * @param null $_
     * @param array{} $args
     * @return array
     * @throws Error
     */
    public function __invoke($_, array $args)
    {
        $user = UserModel::where('email', $args['email'])->first();

        if (!empty($user)) {
            throw ValidationException::withMessages([
                'email' => ['Пользователь с указанныи email уже существует.'],
            ]);
        }

        $user = new UserModel();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = Hash::make($args['password']);
        $user->save();

        return [
            'token' => $user->createToken('register')->plainTextToken,
        ];
    }
}
