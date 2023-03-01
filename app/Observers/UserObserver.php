<?php

namespace App\Observers;

use App\Models\User as UserModel;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Создание токена.
     *
     * @param \App\Models\User $user
     */
    public function created(UserModel $user)
    {
        $token = Str::random(80);
        $hash = hash('sha256', $token);

        $user->api_token = $token;
        $user->save();
    }
}
