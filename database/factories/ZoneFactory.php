<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Zone;

class ZoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * This defines which model the factory is used for, in this case, the `Zone` model.
     *
     * @var string
     */
    protected $model = Zone::class;

    /**
     * Define the model's default state.
     * This method sets the default state for the `Zone` model, defining the attributes that will be used when creating a new zone.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),  // Generates a random name for the zone
        ];
    }
}