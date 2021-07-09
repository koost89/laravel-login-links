<?php

namespace Koost89\UserLogin\Tests\Commands;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Koost89\UserLogin\Commands\UserLoginTokenGenerateCommand;
use Koost89\UserLogin\Tests\TestCase;

class UserLoginGenerateCommandTest extends TestCase
{
    public function test_the_command_can_execute()
    {
        $this->artisan(UserLoginTokenGenerateCommand::class, ['id' => 1])
            ->assertExitCode(0);
    }

    public function test_the_command_fails_when_model_can_not_be_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->artisan(UserLoginTokenGenerateCommand::class, ['id' => "A"]);
    }

}
