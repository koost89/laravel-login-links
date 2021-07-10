<?php

namespace Koost89\UserLogin\Tests\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Koost89\UserLogin\Commands\UserLoginTokenGenerateCommand;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestCase;

class UserLoginGenerateCommandTest extends TestCase
{
    public function test_the_command_can_execute()
    {
        $this->app['config']->set('otl.route.expire_after_visit', true);

        Artisan::call(UserLoginTokenGenerateCommand::class, ['id' => 1]);

        $this->assertEquals(1, UserLoginToken::count());
    }

    public function test_the_command_fails_when_model_can_not_be_found()
    {
        $this->expectException(ModelNotFoundException::class);
        Artisan::call(UserLoginTokenGenerateCommand::class, ['id' => "A"]);
    }
}
