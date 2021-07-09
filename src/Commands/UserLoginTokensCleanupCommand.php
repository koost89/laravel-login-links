<?php

namespace Koost89\UserLogin\Commands;

use Illuminate\Console\Command;
use Koost89\UserLogin\Models\UserLoginToken;

class UserLoginTokensCleanupCommand extends Command
{
    public $signature = 'uli:cleanup';

    public $description = 'Cleans up all the expired tokens in the database.';

    public function handle(): int
    {
        UserLoginToken::query()
            ->where(
                'created_at',
                '<',
                now()->subSeconds(config('otl.route.expiration', 60 * 2))
            )
            ->delete();

        return 0;
    }
}
