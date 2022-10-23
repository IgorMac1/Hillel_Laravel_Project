<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Services\Contract\FileStorageServiceContract;
use App\Services\FileStorageServiceHtml;
use App\Services\FileStorageServiceJson;
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
            ->needs(FileStorageServiceContract::class)
            ->give(FileStorageServiceJson::class);

        $this->app->when(HomeController::class)
            ->needs(FileStorageServiceContract::class)
            ->give(FileStorageServiceHtml::class);


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
