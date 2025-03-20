<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Zone;
use App\Models\Property;
use App\Models\Ubication;

class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * This defines which model the factory is creating instances of.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     * This method specifies the default values for the attributes of the Property model.
     */
    public function definition(): array
    {
        return [
            'ubication_id' => Ubication::factory(),  // Creates a new 'Ubication' model using the factory
            'zone_id' => Zone::factory(),  // Creates a new 'Zone' model using the factory
            'development' => fake()->name(),  // Generates a random development name
            'classification' => fake()->randomElement(["Lujo","Premium","Gama"]),  // Randomly selects a classification
            'type' => fake()->randomElement(["Casa","Departamento","Oficina","Local","Hotel","Bodega","Penthouse"]),  // Randomly selects a property type
            'description' => fake()->name(),  // Generates a random description (fake name in this case)
            'price' => fake()->randomFloat(2, 0, 99999999.99),  // Generates a random price with 2 decimal places
            'currency' => fake()->randomElement(["MDD","MDP"]),  // Randomly selects a currency (MDD or MDP)
            'area_m2' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the area in square meters
            'contruction_m2' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the construction area in square meters
            'price_m2' => fake()->randomFloat(2, 0, 99999999.99),  // Randomly generates the price per square meter
            'rooms' => fake()->randomNumber(),  // Generates a random number of rooms
            'bathrooms' => fake()->randomNumber(),  // Generates a random number of bathrooms
            'amenities' => fake()->name(),  // Generates a random amenities name
            'pet_friendly' => fake()->randomElement(["Si","No"]),  // Randomly selects whether the property is pet-friendly
            'family' => fake()->randomElement(["Infantes","Pareja-Mayor","Pareja-Joven","Familiar","Una-Persona","Negocio"]),  // Randomly selects a family type
            'view' => fake()->randomElement(["Carretera","Mar","Selva","Ciudad","Costa"]),  // Randomly selects a view type
            'operation' => fake()->randomElement(["Venta","Renta","Traspaso"]),  // Randomly selects an operation type (Sale, Rent, or Transfer)
            'contact' => fake()->name(),  // Generates a random contact name
            'contact_type' => fake()->randomElement(["Propietarios","Broker"]),  // Randomly selects a contact type (Owner or Broker)
            'contact_data' => fake()->name(),  // Generates random contact data
            'comission' => fake()->randomFloat(2, 0, 999.99),  // Generates a random commission amount
            'maps' => fake()->name(),  // Generates a random name for maps
            'airbnb_rent' => fake()->randomElement(["Si","No"]),  // Randomly selects whether the property is available for Airbnb rent
            'content' => fake()->paragraphs(3, true),  // Generates random content with 3 paragraphs
            'pdf' => '',  // Default empty string for the PDF field
            'status' => fake()->boolean(),  // Randomly generates a status (true or false)
        ];
    }
}
