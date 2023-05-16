<?php

namespace App\Infrastructure\Providers;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\UserDataSource\UserDataSource;
use App\Infrastructure\Persistence\FileCoinDataSource;
use App\Infrastructure\Persistence\FileUserDataSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(UserDataSource::class, function () {
            return new FileUserDataSource();
        });
        $this->app->bind(CoinDataSource::class, function () {
            return new FileCoinDataSource();
        });
    }
}
