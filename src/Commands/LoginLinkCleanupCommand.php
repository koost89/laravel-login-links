<?php

namespace Koost89\LoginLinks\Commands;

use Illuminate\Console\Command;
use Koost89\LoginLinks\Models\LoginLinkToken;

class LoginLinkCleanupCommand extends Command
{
    public $signature = 'login-links:cleanup';

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
