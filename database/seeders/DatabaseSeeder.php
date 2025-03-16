<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Land;
use App\Models\Property;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'user' => 'Administrador',
            'role' => 'Administrador',
            'password' => bcrypt('Administrador'),
        ]);
        User::create([
            'name' => 'Editor',
            'user' => 'Editor',
            'role' => 'Editor',
            'password' => bcrypt('Editor'),
        ]);
        User::create([
            'name' => 'Consultor',
            'user' => 'Consultor',
            'role' => 'Consultor',
            'password' => bcrypt('Consultor'),
        ]);
        Land::factory()->count(5)->create();
        Property::factory()->count(5)->create();
    }
}
