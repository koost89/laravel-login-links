<?php

namespace Koost89\LoginLinks\Tests\Events;

use Illuminate\Support\Facades\Event;
use Koost89\LoginLinks\Events\LoginLinkGenerated;
use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Tests\TestCase;
use Koost89\LoginLinks\Tests\TestClasses\User;

class LoginLinkGeneratedTest extends TestCase
{
    public function test_it_calls_the_event_after_generating_a_link()
    {
        Event::fake();

        LoginLink::generate(User::first());

        Event::assertDispatched(LoginLinkGenerated::class);
    }

    public function test_the_event_has_the_authenticatable_id_and_class()
    {
        Event::fake();

        $user = User::first();

        LoginLink::generate($user);

        Event::assertDispatched(function (LoginLinkGenerated $event) use ($user) {
            return $event->id === $user->id && $event->class === get_class($user);
        });
    }
}
