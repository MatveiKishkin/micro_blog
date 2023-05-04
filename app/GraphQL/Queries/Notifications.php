<?php

namespace App\GraphQL\Queries;

final class Notifications
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $current_user = \Auth::user()->getModel();

        $notifications = $current_user->notifications;
        $data = [];

        foreach ($notifications as $notification) {
            $data[] = [
                'message' => $notification->data['message'],
                'created_at' => $notification->created_at,
            ];
        }

        return $data;
    }

}
