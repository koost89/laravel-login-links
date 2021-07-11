<?php

namespace Koost89\UserLogin;

use Illuminate\Support\ServiceProvider;
use Koost89\UserLogin\Commands\LoginLinkCleanupCommand;
use Koost89\UserLogin\Commands\LoginLinkCreateCommand;

class LoginLinkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('login-link', function ($app) {
            return new LoginLink();
        });
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/login-links.php',
            'login-links'
        );

        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations/'),
            ], 'login-links-migrations');

            $this->publishes([
                __DIR__ . '/../config/login-links.php' => config_path("login-links.php"),
            ], "login-links-config");
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/login-links.php');

        $this->commands([
            LoginLinkCreateCommand::class,
            LoginLinkCleanupCommand::class,
        ]);
    }
}
