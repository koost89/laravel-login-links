<?php

namespace Koost89\LoginLinks\Commands;

use Illuminate\Console\Command;
use Koost89\LoginLinks\LoginLink;

class LoginLinkCreateCommand extends Command
{
    public $signature = 'login-links:generate {id} {--class=App\Models\User}';

    public $description = 'Generate a signed login url for a specific user.';

    public function handle(LoginLink $UserLogin): int
    {
        $class = $this->option('class');
        $id = $this->argument('id');

        $authenticatable = (new $class)->findOrFail($id);

        $url = $UserLogin->generate($authenticatable);

        $this->info("Your login link: $url");

        return 0;
    }
}
