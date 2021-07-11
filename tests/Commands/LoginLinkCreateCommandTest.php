<?php

namespace Koost89\LoginLinks\Tests\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Koost89\LoginLinks\Commands\LoginLinkCreateCommand;
use Koost89\LoginLinks\Models\LoginLinkToken;
use Koost89\LoginLinks\Tests\TestCase;
use Koost89\LoginLinks\Tests\TestClasses\User;

class LoginLinkCreateCommandTest extends TestCase
{
    public function test_the_command_can_execute()
    {
        $this->assertEquals(0, Artisan::call(LoginLinkCreateCommand::class, ['id' => 1, '--class' => User::class]));
    }

    public function test_the_command_fails_when_model_can_not_be_found()
    {
        $this->expectException(ModelNotFoundException::class);
        Artisan::call(LoginLinkCreateCommand::class, ['id' => "A", '--class' => User::class]);
    }
}
