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
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 99999999.99),
            'currency' => fake()->randomElement(["MDD","MDP"]),
            'area' => fake()->randomFloat(2, 0, 99999999.99),
            'front' => fake()->randomFloat(2, 0, 99999999.99),
            'bottom' => fake()->randomFloat(2, 0, 99999999.99),
            'density' => fake()->regexify('[A-Za-z0-9]{255}'),
            'soil' => fake()->regexify('[A-Za-z0-9]{255}'),
            'view' => fake()->randomElement(["Carretera","Mar","Selva","Ciudad","Costa"]),
            'operation' => fake()->randomElement(["Venta","Renta","Traspaso"]),
            'contact' => fake()->regexify('[A-Za-z0-9]{255}'),
            'contact_type' => fake()->randomElement(["Propietarios","Broker"]),
            'contact_data' => fake()->text(),
            'comission' => fake()->randomFloat(2, 0, 999.99),
            'maps' => fake()->text(),
            'content' => fake()->paragraphs(3, true),
            'pdf' => fake()->text(),
            'status' => fake()->boolean(),
        ];
    }
}
