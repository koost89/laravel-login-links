<?php

namespace Koost89\UserLogin;

use Illuminate\Support\ServiceProvider;
use Koost89\UserLogin\Commands\UserLoginTokenGenerateCommand;
use Koost89\UserLogin\Commands\UserLoginTokensCleanupCommand;

class UserLoginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('user-login', function ($app) {
            return new UserLogin();
        });
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations/'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/../config/otl.php' => config_path("otl.php"),
            ], "config");
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/otl.php');

        $this->commands([
            UserLoginTokenGenerateCommand::class,
            UserLoginTokensCleanupCommand::class,
        ]);
    }
}
