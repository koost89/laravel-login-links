<?php

namespace Koost89\UserLogin\Commands;

use Illuminate\Console\Command;
use Koost89\UserLogin\Models\LoginLinkToken;

class LoginLinkCleanupCommand extends Command
{
    public $signature = 'uli:cleanup';

    public $description = 'Cleans up all the expired tokens in the database.';

    public function handle(): int
    {
        LoginLinkToken::query()
            ->where(
                'created_at',
                '<',
                now()->subSeconds(config('login-links.route.expiration'))
            )
            ->delete();

        return 0;
    }
}
