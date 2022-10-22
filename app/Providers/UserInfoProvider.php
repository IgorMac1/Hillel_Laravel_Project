<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Services\Contracts\UserInfoContract;
use App\Services\UserInfoHtml;
use App\Services\UserInfoJson;
use Illuminate\Support\ServiceProvider;

class UserInfoProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(TestController::class)
            ->needs(UserInfoContract::class)
            ->give(UserInfoJson::class);

        $this->app->when(HomeController::class)
            ->needs(UserInfoContract::class)
            ->give(UserInfoHtml::class);


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
