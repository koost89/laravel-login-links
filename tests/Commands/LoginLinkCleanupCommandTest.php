<?php

namespace Koost89\LoginLinks\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Koost89\LoginLinks\Commands\LoginLinkCleanupCommand;
use Koost89\LoginLinks\Models\LoginLinkToken;
use Koost89\LoginLinks\Tests\TestCase;

class LoginLinkCleanupCommandTest extends TestCase
{
    public function test_it_successfully_deletes_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord(now()->subDays(2));
        $this->createOneTimeLoginTokenRecord(now()->subMinutes(3));

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(0, LoginLinkToken::count());
    }

    public function test_it_does_not_delete_non_expired_tokens()
    {
        $this->createOneTimeLoginTokenRecord();
        $this->createOneTimeLoginTokenRecord(now()->subMinute());

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(2, LoginLinkToken::count());
    }

    public function test_it_uses_the_config_for_expiration_threshold()
    {
        config()->set('login-links.route.expiration', 1);

        $this->createOneTimeLoginTokenRecord(now()->subMinute());

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(0, LoginLinkToken::count());
    }

    public function test_the_expiration_can_support_longer_times()
    {
        config()->set('login-links.route.expiration', 60 * 60 * 24 * 7);

        $this->createOneTimeLoginTokenRecord(now()->subDays(6));

        Artisan::call(LoginLinkCleanupCommand::class);

        $this->assertEquals(1, LoginLinkToken::count());
    }
}
