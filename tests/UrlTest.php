<?php

namespace Koost89\UserLogin\Tests;

use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\LoginLink;

class UrlTest extends TestCase
{
    public function test_it_generates_a_signed_url()
    {
        $url = (new LoginLink())->create(1);

        $this->assertStringContainsString(config('login-links.route.path'), $url);
        $this->assertStringContainsString("auth_id=1", $url);
        $this->assertStringContainsString('expires=', $url);
        $this->assertStringContainsString('signature=', $url);
    }

    public function test_the_user_cannot_be_changed_after_generating_the_url()
    {
        $user = User::inRandomOrder()->first();

        $otherUser = User::inRandomOrder()
            ->where('id', '!=', $user->id)
            ->first();

        $url = (new LoginLink())->create($user->id);

        $badUrl = str_replace('auth_id=' . $user->id, 'auth_id=' . $otherUser->id, $url);

        $this->get($badUrl)
            ->assertForbidden();

        $this->assertGuest();
    }
}
