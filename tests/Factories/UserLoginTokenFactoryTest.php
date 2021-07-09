<?php

namespace Koost89\UserLogin\Tests\Factories;

use Koost89\UserLogin\Database\Factories\UserLoginTokenFactory;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestCase;

class UserLoginTokenFactoryTest extends TestCase
{
    public function test_it_can_create_an_instance_of_the_factory()
    {
        $this->assertInstanceOf(UserLoginTokenFactory::class, UserLoginToken::factory());
    }

    public function test_it_can_store_a_record()
    {
        UserLoginToken::factory()->create();

        $this->assertDatabaseCount('user_login_tokens', 1);
    }
}
