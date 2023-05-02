<?php

namespace App\Base\User\Actions;

use App\Models\User as UserModel;
use App\Repositories\User as UserRepository;
use Illuminate\Support\Facades\Auth;
use ProfilanceGroup\BackendSdk\Support\Response;

class FollowUser
{
    /**
     * BlogPost constructor.
     *
     * @param UserRepository $user_repository
     */
    public function __construct(protected UserRepository $user_repository) {}

    /**
     *  Создание поста.
     *
     * @param int $follows_id
     * @return UserModel|array
     */
    public function follow($follows_id)
    {
        $current_user = Auth::user()->getModel();

        $follows_user = $this->user_repository->find($follows_id);

        if (empty($follows_user)) {
            return Response::error('Пользователя не существует');
        }

        if ($current_user->isFollowing($follows_user->id)) {
            return Response::error('Вы уже подписаны на этого пользователя');
        }

        return $current_user->follow($follows_user->id);
    }
}