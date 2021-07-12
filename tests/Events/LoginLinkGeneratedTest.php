<?php

namespace Koost89\LoginLinks\Tests\Events;

use Illuminate\Support\Facades\Event;
use Koost89\LoginLinks\Events\LoginLinkGenerated;
use Koost89\LoginLinks\Tests\TestCase;

class LoginLinkGeneratedTest extends TestCase
{
    public function test_it_calls_the_event_after_generating_a_link()
    {
        Event::fake();

        $this->createUrlAndToken();

        Event::assertDispatched(LoginLinkGenerated::class);
    }

    public function test_the_event_has_the_authenticatable_id_and_class()
    {
        Event::fake();

        $data = $this->createUrlAndToken();

        Event::assertDispatched(LoginLinkGenerated::class, function ($event) use ($data) {
            return $event->id === $data->user->id && $event->class === get_class($data->user);
        });
    }
}
