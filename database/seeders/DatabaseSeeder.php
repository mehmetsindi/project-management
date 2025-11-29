<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),                    // fake() → $this->faker
            'email' => $this->faker->unique()->safeEmail(),    // fake() → $this->faker
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // ya da Hash::make('password')
            'remember_token' => Str::random(10),
            'is_super_admin' => false,
        ];
    }
}