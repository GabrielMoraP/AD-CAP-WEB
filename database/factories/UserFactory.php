<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     * This is used to set the default password for created users.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     * This method defines the default state of a `User` model, including random values for the attributes.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),  // Randomly generated name for the user
            'user' => fake()->name(),  // Unique random username
            'role' => fake()->randomElement(["Administrador","Editor","Consultor"]),  // Random role for the user
            'avatar' => 1, // Set a random avatar number
            'password' => static::$password ??= Hash::make('password'),  // Set the default password or use a predefined password
            'remember_token' => Str::random(10),  // Generate a random remember token
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     * This method allows the creation of a user with no verification date.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_verified_at' => null,  // Set the verification timestamp to null (unverified)
        ]);
    }
}