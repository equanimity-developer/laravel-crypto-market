<?php

namespace App\Providers;

use App\Adapters\CoinGeckoAdapter;
use App\Adapters\Interfaces\CryptoAdapterInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CryptoAdapterInterface::class, CoinGeckoAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
