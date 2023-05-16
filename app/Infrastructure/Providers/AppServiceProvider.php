<?php

namespace App\Infrastructure\Providers;

use App\Application\DataSources\CoinDataSource;
use App\Application\DataSources\UserDataSource;
use App\Application\DataSources\WalletDataSource;
use App\Infrastructure\Persistence\FileCoinDataSource;
use App\Infrastructure\Persistence\FileUserDataSource;
use App\Infrastructure\Persistence\FileWalletDataSource;
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

        $this->app->bind(WalletDataSource::class, function () {
            return new FileWalletDataSource();
        });
        $this->app->bind(CoinDataSource::class, function () {
            return new FileCoinDataSource();
        });
    }
}
