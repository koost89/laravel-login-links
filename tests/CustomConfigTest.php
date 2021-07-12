<?php

namespace Koost89\LoginLinks\Tests;

use Koost89\LoginLinks\Facades\LoginLink;
use Koost89\LoginLinks\Tests\TestClasses\User;

class CustomConfigTest extends TestCase
{
    public function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        config()->set('login-links.route', [
           'path' => '/custom-route',
           'redirect_after_login' => '/test-redirect',
           'allowed_visits_before_expiration' => 0,
           'additional_middleware' => ['guest'],
       ]);
    }

    public function test_it_loads_the_path_from_the_config()
    {
        $url = LoginLink::generate(User::first());
        $this->assertStringContainsString('/custom-route', $url);
    }

    public function test_it_redirects_to_the_specified_url()
    {
        $url = LoginLink::generate(User::first());
        $this->get($url)
           ->assertRedirect('/test-redirect');
    }

    public function test_it_adds_the_specified_middleware()
    {
        $this->assertTrue(
            in_array(
                'guest',
                $this->app['router']->getRoutes()->getByName('login-links.login')->middleware()
            )
        );
    }
}
