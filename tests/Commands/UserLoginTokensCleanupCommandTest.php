<?php

namespace Koost89\UserLogin\Tests\Commands;

use Koost89\UserLogin\Commands\UserLoginTokensCleanupCommand;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestCase;

class UserLoginTokensCleanupCommandTest extends TestCase
{
    public function test_the_command_can_execute()
    {
        $this->artisan(UserLoginTokensCleanupCommand::class)
            ->assertExitCode(0);
    }

    public function test_it_successfully_deletes_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord(now()->subDays(2));
        $this->createOneTimeLoginTokenRecord(now()->subMinutes(3));

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 0);
    }

    public function test_it_does_not_delete_non_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord(now()->subMinute());
        $this->createOneTimeLoginTokenRecord(now()->subMinutes(2));
        $this->createOneTimeLoginTokenRecord();


        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 3);
    }

    public function test_it_uses_the_config_for_expiration_threshold()
    {
        $this->app['config']->set('otl.route.expiration', 1);

        $this->createOneTimeLoginTokenRecord(now()->subMinute());

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 0);
    }

    public function test_the_expiration_can_support_longer_times()
    {
        $this->app['config']->set('otl.route.expiration', 60 * 60 * 24 * 7);

        $this->createOneTimeLoginTokenRecord(now()->subDays(6));

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 1);
    }
}
