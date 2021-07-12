<?php

namespace Koost89\LoginLinks\Tests;

use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Models\LoginLinkToken;
use Koost89\LoginLinks\Tests\TestClasses\User;
use TheSeer\Tokenizer\Token;

class LoginTest extends TestCase
{
    public function test_it_can_login_with_the_signed_url()
    {
        $data = $this->createUrlAndToken();
        $this->get($data->url);

        $this->assertAuthenticatedAs($data->user);
    }

    public function test_it_redirects_after_login()
    {
        $data = $this->createUrlAndToken();

        $this->get($data->url)
            ->assertRedirect(config('login-links.route.redirect_after_login'));
    }

    public function test_it_stores_a_token_by_default()
    {
        $this->createUrl(User::inRandomOrder()->first());
        $this->assertEquals(1, LoginLinkToken::count());
    }

    public function test_if_configured_it_can_expire_after_a_single_login()
    {
        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token);

        $this->assertEquals(0, LoginLinkToken::count());
    }

    public function test_the_user_cannot_login_after_the_link_expires()
    {
        config()->set('login-links.route.expiration', -10);

        $data = $this->createUrlAndToken();

        $this->followingRedirects()
            ->get($data->url)
            ->assertForbidden();

        $this->assertGuest();
    }

    public function test_the_amount_of_visits_is_increased_when_visiting_a_link()
    {
        config()->set('login-links.route.allowed_visits_before_expiration', 2);

        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token);

        $this->assertEquals(1, $data->token->fresh()->visits);
    }

    public function test_it_deletes_the_token_after_the_specified_amount_of_logins()
    {
        config()->set('login-links.route.allowed_visits_before_expiration', 1);

        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token);

        $this->assertNull($data->token->fresh());
    }

    public function test_it_can_still_login_after_the_first_time_if_allowed_visits_is_more_than_one()
    {
        config()->set('login-links.route.allowed_visits_before_expiration', 2);

        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token->fresh());

        \Auth::logout();

        LoginLink::login($data->user->id, get_class($data->user), $data->token->fresh());

        $this->assertAuthenticatedAs($data->user);

    }
}
