<?php

namespace Koost89\UserLogin\Tests\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Koost89\UserLogin\Commands\LoginLinkCreateCommand;
use Koost89\UserLogin\Models\UserLoginToken;
use Koost89\UserLogin\Tests\TestCase;

class LoginLinkCreateCommandTest extends TestCase
{
    public function test_the_command_can_execute()
    {
        Artisan::call(LoginLinkCreateCommand::class, ['id' => 1]);
        $this->assertEquals(1, UserLoginToken::count());
    }

    public function test_the_command_fails_when_model_can_not_be_found()
    {
        $this->expectException(ModelNotFoundException::class);
        Artisan::call(LoginLinkCreateCommand::class, ['id' => "A"]);
    }
}
