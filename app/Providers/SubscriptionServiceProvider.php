<?php

namespace App\Providers;

use Domain\Repositories\UserRepository;
use Domain\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('SubscriptionService', function () {
            return new SubscriptionService(new UserRepository());
        });
    }
}
