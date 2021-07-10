<?php

namespace Koost89\UserLogin\Tests;

use Illuminate\Database\Schema\Blueprint;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
        ];
    }

    protected function setUpDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->softDeletes();
        });

        $app['config']->set('auth.providers.users.model', User::class);

        $this->loadMigrationsFrom('database/migrations');

        User::create(['email' => 'test@test.com']);
        User::create(['email' => 'test2@test.com']);
        User::create(['email' => 'test3@test.com']);
        User::create(['email' => 'test4@test.com']);
        User::create(['email' => 'test5@test.com']);
    }

    protected function createOneTimeLoginTokenRecord($created_at = null, $url = 'test.com')
    {
        if (! $created_at) {
            $created_at = now();
        }

        (new UserLoginToken())->forceFill([
            'url' => $url,
            'created_at' => $created_at,
        ])->save();
    }
}
