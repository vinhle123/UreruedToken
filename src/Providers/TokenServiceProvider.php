<?php

namespace Urerued\UreruedToken\Providers;

use Illuminate\Support\ServiceProvider;
use Urerued\UreruedToken\TokenManager;

class TokenServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->singleton(TokenManager::class, function ($app) {
            return new TokenManager();
        });
    }

    public function boot()
    {

    }
}
