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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);
        Land::factory()->count(5)->create();
        Property::factory()->count(5)->create();
    }
}
