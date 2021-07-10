<?php

namespace Koost89\UserLogin\Tests;

use Koost89\UserLogin\LoginLink;
use Koost89\UserLogin\Tests\TestClasses\User;

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

    public function test_the_link_can_have_extra_parameters()
    {
        $user = User::inRandomOrder()->first();
        $url = (new LoginLink())->create($user->id, ['extra_param' => 1]);

        $this->assertStringContainsString('extra_param=1', $url);
    }

    public function test_the_link_is_still_valid_with_an_extra_parameter()
    {
        $user = User::inRandomOrder()->first();
        $url = (new LoginLink())->create($user->id, ['extra_param' => 1]);

        $this->get($url);

        $this->assertAuthenticatedAs($user);
    }
}
