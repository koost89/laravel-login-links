<?php

namespace Koost89\LoginLinks\Tests;

use Koost89\LoginLinks\Tests\TestClasses\User;

class UrlTest extends TestCase
{
    public function test_it_generates_a_valid_signed_url()
    {
        $data = $this->createUrlAndToken();

        $this->assertIsValidLoginUrl($data->url, $data->user);
    }

    public function test_the_user_cannot_be_changed_after_generating_the_url()
    {
        $data = $this->createUrlAndToken();

        $otherUser = User::inRandomOrder()
            ->where('id', '!=', $data->user->id)
            ->first();


        $badUrl = str_replace('auth_id=' . $data->user->id, 'auth_id=' . $otherUser->id, $data->url);

        $this->get($badUrl)
            ->assertForbidden();

        $this->get($data->url . 'added')
            ->assertForbidden();

        $this->assertGuest();
    }
}
