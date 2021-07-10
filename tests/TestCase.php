<?php

namespace Koost89\UserLogin\Tests;

use Illuminate\Database\Schema\Blueprint;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\LoginLinkServiceProvider;
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
            LoginLinkServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function setUpDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('remember_token')->nullable();
            $table->softDeletes();
        });

        include_once __DIR__ . '/../database/migrations/2021_07_07_120000_create_user_login_tokens_table.php';

        (new \CreateUserLoginTokensTable())->up();


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
