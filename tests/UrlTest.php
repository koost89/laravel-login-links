<?php

namespace Koost89\UserLogin\Tests;

use Illuminate\Support\Str;
use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\UserLogin;

class UrlTest extends TestCase
{
    public function test_it_generates_a_signed_url()
    {
        $url = (new UserLogin())->create(1);

        $this->assertStringContainsString("/otl", $url);
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

        $url = (new UserLogin())->create($user->id);

        $badUrl = str_replace('auth_id=' . $user->id, 'auth_id=' . $otherUser->id, $url);

        $this->get($badUrl)
            ->assertForbidden();

        $this->assertGuest();
    }
}
