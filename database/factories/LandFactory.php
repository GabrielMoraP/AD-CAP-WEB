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
     * This defines which model the factory is creating instances of.
     *
     * @var string
     */
    protected $model = Land::class;

    /**
     * Define the model's default state.
     * This method specifies the default values for the attributes of the Land model.
     */
    public function definition(): array
    {
        return [
            'ubication_id' => Ubication::factory(),  // Creates a new 'Ubication' model using the factory
            'zone_id' => Zone::factory(),  // Creates a new 'Zone' model using the factory
            'classification' => fake()->randomElement(["Residencial","Unifamiliar","Industrial","Comercial"]),  // Randomly selects a classification
            'description' => fake()->name(),  // Generates a random description (fake name in this case)
            'price' => fake()->randomFloat(2, 0, 99999999.99),  // Generates a random price with 2 decimal places
            'currency' => fake()->randomElement(["MDD","MDP"]),  // Randomly selects a currency (MDD or MDP)
            'area' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the area in square meters
            'front' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the front measurement
            'bottom' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the bottom measurement
            'density' => fake()->name(),  // Generates a random density name
            'soil' => fake()->name(),  // Generates a random soil name
            'view' => fake()->randomElement(["Carretera","Mar","Selva","Ciudad","Costa"]),  // Randomly selects a view type
            'operation' => fake()->randomElement(["Venta","Renta","Traspaso"]),  // Randomly selects an operation type (Sale, Rent, or Transfer)
            'contact' => fake()->name(),  // Generates a random contact name
            'contact_type' => fake()->randomElement(["Propietarios","Broker"]),  // Randomly selects a contact type (Owner or Broker)
            'contact_data' => fake()->name(),  // Generates random contact data
            'comission' => fake()->randomFloat(2, 0, 999.99),  // Generates a random commission amount
            'maps' => fake()->name(),  // Generates a random name for maps
            'content' => fake()->paragraphs(3, true),  // Generates a random content with 3 paragraphs
            'pdf' => '',  // Default empty string for the PDF field
            'status' => fake()->boolean(),  // Randomly generates a status (true or false)
        ];
    }
}