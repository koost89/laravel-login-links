<?php

namespace Koost89\UserLogin\Commands;

use Illuminate\Console\Command;
use Koost89\UserLogin\LoginLink;

class LoginLinkCreateCommand extends Command
{
    public $signature = 'uli:create {id}';

    public $description = 'Generate a signed login url for a specific user.';

    public function handle(LoginLink $UserLogin): int
    {
        $url = $UserLogin->create($this->argument('id'));

        $this->info("Your login link: $url");

        return 0;
    }
}
