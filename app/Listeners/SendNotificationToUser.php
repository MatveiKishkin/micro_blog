<?php

namespace App\Listeners;

use App\Base\Order\Events\Edit as EditOrderEvent;
use App\Events\UserNotification;
use App\Notifications\NotifyFollowUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserNotification  $event
     * @return void
     */
    public function send(UserNotification $event)
    {
        \Notification::send($event->user, new NotifyFollowUser($event->user));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            UserNotification::class => 'send',
        ];
    }
}
