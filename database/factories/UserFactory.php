<?php

namespace Koost89\LoginLinks\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Koost89\LoginLinks\Tests\TestClasses\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => bcrypt($this->faker->password()),
        ];
    }
}
