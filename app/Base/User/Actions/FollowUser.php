<?php

namespace App\Base\User\Actions;

use App\Events\UserNotification as UserNotificationEvent;
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
     * @return int|array
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

        $current_user->follow($follows_user->id);

        /**
         * Событие отправки уведомления.
         */
        UserNotificationEvent::dispatch($follows_user);

        return $follows_user->id;
    }
}