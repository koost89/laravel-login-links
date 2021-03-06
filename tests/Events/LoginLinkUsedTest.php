<?php

namespace Koost89\LoginLinks\Tests\Events;

use Illuminate\Support\Facades\Event;
use Koost89\LoginLinks\Events\LoginLinkUsed;
use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Tests\TestCase;

class LoginLinkUsedTest extends TestCase
{
    public function test_it_calls_the_event_after_the_link_is_used()
    {
        Event::fake();

        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token);

        Event::assertDispatched(LoginLinkUsed::class);
    }

    public function test_the_event_has_the_authenticatable_id_and_class()
    {
        Event::fake();

        $data = $this->createUrlAndToken();

        LoginLink::login($data->user->id, get_class($data->user), $data->token);

        Event::assertDispatched(LoginLinkUsed::class, function ($event) use ($data) {
            return (int) $event->id === $data->user->id && $event->class === get_class($data->user);
        });
    }
}
