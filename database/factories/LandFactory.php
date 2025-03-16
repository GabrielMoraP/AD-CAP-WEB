<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Zone;
use App\Models\Land;
use App\Models\Ubication;

class LandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Land::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ubication_id' => Ubication::factory(),
            'zone_id' => Zone::factory(),
            'classification' => fake()->randomElement(["Residencial","Unifamiliar","Industrial","Comercial"]),
            'description' => fake()->name(),
            'price' => fake()->randomFloat(2, 0, 99999999.99),
            'currency' => fake()->randomElement(["MDD","MDP"]),
            'area' => fake()->randomFloat(2, 0, 99999999.99),
            'front' => fake()->randomFloat(2, 0, 99999999.99),
            'bottom' => fake()->randomFloat(2, 0, 99999999.99),
            'density' => fake()->name(),
            'soil' => fake()->name(),
            'view' => fake()->randomElement(["Carretera","Mar","Selva","Ciudad","Costa"]),
            'operation' => fake()->randomElement(["Venta","Renta","Traspaso"]),
            'contact' => fake()->name(),
            'contact_type' => fake()->randomElement(["Propietarios","Broker"]),
            'contact_data' => fake()->name(),
            'comission' => fake()->randomFloat(2, 0, 999.99),
            'maps' => fake()->name(),
            'content' => fake()->paragraphs(3, true),
            'pdf' => '',
            'status' => fake()->boolean(),
        ];
    }
}
