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
        UserLoginToken::factory()->create(['created_at' => now()->subDays(2)]);
        UserLoginToken::factory()->create(['created_at' => now()->subMinutes(3)]);

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 0);
    }

    public function test_it_does_not_delete_non_expired_tokens()
    {
        UserLoginToken::factory()->create(['created_at' => now()->subMinute()]);
        UserLoginToken::factory()->create(['created_at' => now()->subMinutes(2)]);
        UserLoginToken::factory()->create();

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 3);
    }

    public function test_it_uses_the_config_for_expiration_threshold()
    {
        $this->app['config']->set('otl.route.expiration', 1);

        UserLoginToken::factory()->create(['created_at' => now()->subMinute()]);

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 0);
    }

    public function test_the_expiration_can_support_longer_times()
    {
        $this->app['config']->set('otl.route.expiration', 60 * 60 * 24 * 7);

        UserLoginToken::factory()->create(['created_at' => now()->subDays(6)]);

        $this->artisan(UserLoginTokensCleanupCommand::class);

        $this->assertDatabaseCount('user_login_tokens', 1);
    }

}
