<?php

namespace Koost89\LoginLinks\Tests;

use Illuminate\Database\Schema\Blueprint;
use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Helpers\URLHelper;
use Koost89\LoginLinks\LoginLinkServiceProvider;
use Koost89\LoginLinks\Models\LoginLinkToken;
use Koost89\LoginLinks\Tests\TestClasses\OtherAuthenticatable;
use Koost89\LoginLinks\Tests\TestClasses\Team;
use Koost89\LoginLinks\Tests\TestClasses\User;
use Orchestra\Testbench\TestCase as Orchestra;

class CustomGuardTest extends TestCase
{
    public function test_it_can_generate_a_link_for_custom_models()
    {
        $team = Team::inRandomOrder()->first();

        $this->assertStringContainsString('auth_type', LoginLink::generate($team));
    }

    public function test_the_generated_link_is_valid_for_custom_models()
    {
        $this->app['config']->set('auth.providers.users.model', OtherAuthenticatable::class);

        $otherAuthenticatable = OtherAuthenticatable::inRandomOrder()->first();

        $url = LoginLink::generate($otherAuthenticatable);

        $base64Token = $this->between('auth_type=', '&expires=', $url);
        $this->assertEquals(URLHelper::encodedStringToClass($base64Token), OtherAuthenticatable::class);
    }

    public function test_it_can_support_multiple_guards()
    {
        $this->app['config']->set('auth.providers', [
            'users' => [
                'driver' => 'eloquent',
                'model' => User::class,
            ],
            'teams' => [
                'driver' => 'eloquent',
                'model' => Team::class
            ]
        ]);

        $this->app['config']->set('auth.guards', [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
            'team' => [
                'driver' => 'session',
                'provider' => 'teams',
            ],
        ]);

        $user = User::inRandomOrder()->first();
        $team = Team::inRandomOrder()->first();

        $userUrl = LoginLink::generate($user);
        $teamUrl = LoginLink::generate($team);

        $this->get($userUrl);
        $this->get($teamUrl);

        $this->assertAuthenticatedAs($user);
        $this->assertAuthenticatedAs($team, 'team');

    }
}
