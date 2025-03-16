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
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ubication_id' => Ubication::factory(),
            'zone_id' => Zone::factory(),
            'development' => fake()->name(),
            'classification' => fake()->randomElement(["Lujo","Premium","Gama"]),
            'type' => fake()->randomElement(["Casa","Departamento","Oficina","Local","Hotel","Bodega","Penthouse"]),
            'description' => fake()->name(),
            'price' => fake()->randomFloat(2, 0, 99999999.99),
            'currency' => fake()->randomElement(["MDD","MDP"]),
            'area_m2' => fake()->randomFloat(2, 0, 99999999.99),
            'contruction_m2' => fake()->randomFloat(2, 0, 99999999.99),
            'price_m2' => fake()->randomFloat(2, 0, 99999999.99),
            'rooms' => fake()->randomNumber(),
            'bathrooms' => fake()->randomNumber(),
            'amenities' => fake()->name(),
            'pet_friendly' => fake()->randomElement(["Si","No"]),
            'family' => fake()->randomElement(["Infantes","Pareja-Mayor","Pareja-Joven","Familiar","Una-Persona","Negocio"]),
            'view' => fake()->randomElement(["Carretera","Mar","Selva","Ciudad","Costa"]),
            'operation' => fake()->randomElement(["Venta","Renta","Traspaso"]),
            'contact' => fake()->name(),
            'contact_type' => fake()->randomElement(["Propietarios","Broker"]),
            'contact_data' => fake()->name(),
            'comission' => fake()->randomFloat(2, 0, 999.99),
            'maps' => fake()->name(),
            'airbnb_rent' => fake()->randomElement(["Si","No"]),
            'content' => fake()->paragraphs(3, true),
            'pdf' => fake()->name(),
            'status' => fake()->boolean(),
        ];
    }
}
