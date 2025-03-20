<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Ubication;

class UbicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * This defines which model the factory is creating instances of.
     *
     * @var string
     */
    protected $model = Ubication::class;

    /**
     * Define the model's default state.
     * This method specifies the default values for the attributes of the Ubication model.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),  // Generates a random name for the Ubication model
        ];
    }
}