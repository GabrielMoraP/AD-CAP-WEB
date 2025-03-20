<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the 'zones' table.
     */
    public function up(): void
    {
        // Creating the 'zones' table
        Schema::create('zones', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column as the primary key
            $table->string('name');  // Creates a 'name' column to store the name of the zone
            $table->timestamps();  // Creates 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the 'zones' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');  // Drops the 'zones' table if it exists
    }
};