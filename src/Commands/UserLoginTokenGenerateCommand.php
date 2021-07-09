<?php

namespace Koost89\UserLogin\Commands;

use Illuminate\Console\Command;
use Koost89\UserLogin\UserLogin;

class UserLoginTokenGenerateCommand extends Command
{
    public $signature = 'uli:generate {id}';

    public $description = 'Generate a signed login url for a specific user.';

    public function handle(UserLogin $UserLogin): int
    {
        $url = $UserLogin->create($this->argument('id'));

        $this->info("Your login link: $url");

        return 0;
    }
}
