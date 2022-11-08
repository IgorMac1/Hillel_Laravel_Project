<?php

namespace App\Listeners;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserEventListener
{
    public function handleLogin($event)
    {
        Cart::instance('cart')->restore($event->user->id);
        Cart::instance('wishlist')->restore($event->user->id);
    }

    public function handleLogout($event)
    {
        if (Cart::instance('cart')->count() > 0) {
            Cart::instance('cart')->store($event->user->id);
        }
        if (Cart::instance('wishlist')->count() > 0) {
            Cart::instance('wishlist')->store($event->user->id);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            \Illuminate\Auth\Events\Login::class,
            'App\Listeners\UserEventListener@handleLogin'
        );
        $events->listen(
            \Illuminate\Auth\Events\Logout::class,
            'App\Listeners\UserEventListener@handleLogout'
        );
    }
}
