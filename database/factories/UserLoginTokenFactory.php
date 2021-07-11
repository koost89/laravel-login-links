<?php

namespace Koost89\UserLogin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Koost89\UserLogin\Models\LoginLinkToken;

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
