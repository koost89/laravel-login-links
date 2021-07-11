<?php

namespace Koost89\LoginLinks\Tests\Traits;

use Koost89\LoginLinks\Tests\TestCase;
use Koost89\LoginLinks\Tests\TestClasses\User;

class CanLoginWithLinkTest extends TestCase
{
    public function test_it_can_get_the_guard_name()
    {
        $user = User::first();

        $this->assertEquals('web', $user->getGuardName());
    }

    public function test_it_can_generate_an_url_from_authenticatable()
    {
        $user = User::first();

        $url = $user->generateLoginLink();

        $this->assertIsValidLoginUrl($url, $user);
    }
}
