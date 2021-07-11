<?php

namespace Koost89\LoginLinks\Tests;

use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Tests\TestClasses\User;

class UrlTest extends TestCase
{
    public function test_it_generates_a_valid_signed_url()
    {
        $user = User::first();
        $url = LoginLink::generate($user);

        $this->assertIsValidLoginUrl($url, $user);
    }

    public function test_the_user_cannot_be_changed_after_generating_the_url()
    {
        $user = User::inRandomOrder()->first();

        $otherUser = User::inRandomOrder()
            ->where('id', '!=', $user->id)
            ->first();

        $url = LoginLink::generate($user);

        $badUrl = str_replace('auth_id=' . $user->id, 'auth_id=' . $otherUser->id, $url);

        $this->get($badUrl)
            ->assertForbidden();

        $this->get($url . 'added')
            ->assertForbidden();

        $this->assertGuest();
    }
}
