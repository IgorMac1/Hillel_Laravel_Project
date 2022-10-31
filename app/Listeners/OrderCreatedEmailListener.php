<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Notifications\OrderCreatedEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedEmailListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        $event->order->notify(new OrderCreatedEmailNotification);
    }
}
