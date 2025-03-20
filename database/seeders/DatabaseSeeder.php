<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Land;
use App\Models\Property;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * This method populates the database with initial data.
     */
    public function run(): void
    {
        // Creating an 'Administrador' user with predefined credentials
        User::create([
            'name' => 'Administrador',  // User's name
            'user' => 'Administrador',  // Username
            'role' => 'Administrador',  // User's role
            'password' => bcrypt('Administrador'),  // User's password (hashed)
            'avatar' => 1,  // Avatar ID
        ]);
        
        // Creating an 'Editor' user with predefined credentials
        User::create([
            'name' => 'Editor',
            'user' => 'Editor',
            'role' => 'Editor',
            'password' => bcrypt('Editor'),
            'avatar' => 2,
        ]);
        
        // Creating a 'Consultor' user with predefined credentials
        User::create([
            'name' => 'Consultor',
            'user' => 'Consultor',
            'role' => 'Consultor',
            'password' => bcrypt('Consultor'),
            'avatar' => 3,
        ]);
        
        // Creating 5 records of 'Property' using a factory
        User::factory()->count(5)->create();

        // Creating 5 records of 'Land' using a factory
        Land::factory()->count(5)->create();
        
        // Creating 5 records of 'Property' using a factory
        Property::factory()->count(5)->create();
    }
}