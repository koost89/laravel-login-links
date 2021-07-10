<?php

namespace Koost89\UserLogin\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Koost89\UserLogin\Commands\LoginLinkCleanupCommand;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestCase;

class LoginLinkCleanupCommandTest extends TestCase
{
    public function test_it_successfully_deletes_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord(now()->subDays(2));
        $this->createOneTimeLoginTokenRecord(now()->subMinutes(3));

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(0, UserLoginToken::count());
    }

    public function test_it_does_not_delete_non_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord(now()->subMinute());
        $this->createOneTimeLoginTokenRecord(now()->subMinutes(2));
        $this->createOneTimeLoginTokenRecord();


        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(3, UserLoginToken::count());
    }

    public function test_it_uses_the_config_for_expiration_threshold()
    {
        $this->app['config']->set('login-links.route.expiration', 1);

        $this->createOneTimeLoginTokenRecord(now()->subMinute());

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(0, UserLoginToken::count());
    }

    public function test_the_expiration_can_support_longer_times()
    {
        $this->app['config']->set('login-links.route.expiration', 60 * 60 * 24 * 7);

        $this->createOneTimeLoginTokenRecord(now()->subDays(6));

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(1, UserLoginToken::count());
    }
}
