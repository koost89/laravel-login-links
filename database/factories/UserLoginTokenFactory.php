<?php

namespace Koost89\LoginLinks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Koost89\LoginLinks\Models\LoginLinkToken;

class UserLoginTokenFactory extends Factory
{
    protected $model = LoginLinkToken::class;

    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
        ];
    }
}
