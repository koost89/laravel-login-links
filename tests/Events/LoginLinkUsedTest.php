<?php

namespace Koost89\LoginLinks\Tests\Events;

use Illuminate\Support\Facades\Event;
use Koost89\LoginLinks\Events\LoginLinkUsed;
use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Tests\TestCase;
use Koost89\LoginLinks\Tests\TestClasses\User;

class LoginLinkUsedTest extends TestCase
{
    public function test_it_calls_the_event_after_the_link_is_used()
    {
        Event::fake();

        $url = LoginLink::generate(User::first());

        $this->get($url);

        Event::assertDispatched(LoginLinkUsed::class);
    }

    public function test_the_event_has_the_authenticatable_id_and_class()
    {
        Event::fake();

        $user = User::first();

        $url = LoginLink::generate($user);

        $this->get($url);

        Event::assertDispatched(LoginLinkUsed::class, function ($event) use ($user) {
            return (int) $event->id === $user->id && $event->class === get_class($user);
        });

    }
}
