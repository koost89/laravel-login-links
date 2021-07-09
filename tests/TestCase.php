<?php

namespace Koost89\UserLogin\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
        ];
    }

    protected function setUpDatabase(): void
    {
        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom('database/migrations');

        User::factory()
            ->count(5)
            ->create();
    }
}
