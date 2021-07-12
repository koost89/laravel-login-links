<?php

namespace Koost89\LoginLinks\Tests;

use Illuminate\Database\Schema\Blueprint;
use Koost89\LoginLinks\Helpers\URLHelper;
use Koost89\LoginLinks\LoginLinkServiceProvider;
use Koost89\LoginLinks\Models\LoginLinkToken;
use Koost89\LoginLinks\Tests\TestClasses\OtherAuthenticatable;
use Koost89\LoginLinks\Tests\TestClasses\Team;
use Koost89\LoginLinks\Tests\TestClasses\User;
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

        $app['db']->connection()->getSchemaBuilder()->create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('remember_token')->nullable();
            $table->softDeletes();
        });

        $app['db']->connection()->getSchemaBuilder()->create('other_authenticatables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('remember_token')->nullable();
            $table->softDeletes();
        });


        include_once __DIR__ . '/../database/migrations/2021_07_07_120000_create_login_link_tokens_table.php';

        (new \CreateLoginLinkTokensTable())->up();

        User::create(['email' => 'test@test.com']);
        User::create(['email' => 'test2@test.com']);
        User::create(['email' => 'test3@test.com']);
        User::create(['email' => 'test4@test.com']);
        User::create(['email' => 'test5@test.com']);

        Team::create(['email' => 'test6@test.com']);
        Team::create(['email' => 'test7@test.com']);

        OtherAuthenticatable::create(['email' => 'test8@test.com']);
        OtherAuthenticatable::create(['email' => 'test9@test.com']);
    }

    protected function createOneTimeLoginTokenRecord($created_at = null, $url = 'test.com')
    {
        if (! $created_at) {
            $created_at = now();
        }

        (new LoginLinkToken())->forceFill([
            'url' => $url,
            'created_at' => $created_at,
        ])->save();
    }

    public function getUser()
    {
        return User::inRandomOrder()->first();
    }

    public function createUrl($user)
    {
        return $user->generateLoginLink();
    }

    public function getToken($url)
    {
        return LoginLinkToken::where('url', $url)->first();
    }

    public function createUrlAndToken()
    {
        $user = $this->getUser();
        $url = $this->createUrl($user);
        $token = $this->getToken($url);

        return (object) ['user' => $user, 'url' =>  $url, 'token' => $token];
    }

    protected function between($starting_word, $ending_word, $string)
    {
        $arr = explode($starting_word, $string);
        if (isset($arr[1])) {
            $arr = explode($ending_word, $arr[1]);

            return $arr[0];
        }

        return '';
    }

    protected function assertIsValidLoginUrl($url, $authenticatable)
    {
        $this->assertStringContainsString(config('login-links.route.path'), $url);
        $this->assertStringContainsString("auth_id=$authenticatable->id", $url);
        $this->assertStringContainsString('auth_type=' . urlencode(URLHelper::classToEncodedString(get_class($authenticatable))), $url);
        $this->assertStringContainsString("expires=", $url);
        $this->assertStringContainsString("signature=", $url);
    }
}
