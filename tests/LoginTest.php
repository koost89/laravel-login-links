<?php

namespace Koost89\UserLogin\Tests;

use Koost89\UserLogin\UserLogin;
use Koost89\UserLogin\Tests\TestClasses\User;
use Koost89\UserLogin\Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_it_can_login_with_the_signed_url()
    {
        $user = User::inRandomOrder()->first();

        $url = (new UserLogin())->create($user->id);

        $this->get($url)
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_it_stores_a_token_when_expire_after_visit_is_true()
    {
        $this->app['config']->set('otl.route.expire_after_visit', true);

        $user = User::inRandomOrder()->first();

        (new UserLogin())->create($user->id);

        $this->assertDatabaseCount('user_login_tokens', 1);
    }

    public function test_if_configured_it_can_expire_after_a_single_login()
    {
        $this->app['config']->set('otl.route.expire_after_visit', true);

        $user = User::inRandomOrder()->first();

        $url = (new UserLogin())->create($user->id);

        $this->get($url)
            ->assertRedirect('/');

        $this->assertDatabaseCount('user_login_tokens', 0);
    }

    public function test_the_user_cannot_login_after_the_link_expires()
    {
        $this->app['config']->set('otl.route.expiration', -10);

        $user = User::inRandomOrder()->first();

        $url = (new UserLogin())->create($user->id);

        $this->get($url)
            ->assertForbidden();

        $this->assertGuest();
    }
}
