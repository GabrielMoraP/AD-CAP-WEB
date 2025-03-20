<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the 'properties' table.
     */
    public function up(): void
    {
        // Creating the 'properties' table
        Schema::create('properties', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column as the primary key
            $table->foreignId('ubication_id')->nullable()->constrained("ubications")->nullOnDelete();  // Foreign key referencing 'ubications', nullable, with a null on delete
            $table->foreignId('zone_id')->nullable()->constrained("zones")->nullOnDelete();  // Foreign key referencing 'zones', nullable, with a null on delete
            $table->string('development', 255);  // Creates a 'development' column to store the development name (up to 255 characters)
            $table->enum('classification', ["Lujo","Premium","Gama"]);  // Creates a 'classification' column with predefined values
            $table->enum('type', ["Casa","Departamento","Oficina","Local","Hotel","Bodega","Penthouse"]);  // Creates a 'type' column with predefined values for property types
            $table->text('description');  // Creates a 'description' column to store the property's description
            $table->decimal('price', 10, 2);  // Creates a 'price' column to store the property's price (up to 10 digits with 2 decimals)
            $table->enum('currency', ["MDD","MDP"]);  // Creates a 'currency' column with predefined values for the currency (MDD or MDP)
            $table->decimal('area_m2', 10, 2);  // Creates an 'area_m2' column to store the property's area in square meters
            $table->decimal('contruction_m2', 10, 2);  // Creates a 'contruction_m2' column to store the constructed area in square meters
            $table->unsignedInteger('rooms');  // Creates a 'rooms' column to store the number of rooms
            $table->unsignedInteger('bathrooms');  // Creates a 'bathrooms' column to store the number of bathrooms
            $table->enum('pet_friendly', ["Si","No"]);  // Creates a 'pet_friendly' column to indicate if pets are allowed
            $table->enum('family', ["Infantes","Pareja-Mayor","Pareja-Joven","Familiar","Una-Persona","Negocio"]);  // Creates a 'family' column to describe the target family type
            $table->enum('view', ["Carretera","Mar","Selva","Ciudad","Costa"]);  // Creates a 'view' column with predefined values for the property's view
            $table->enum('operation', ["Venta","Renta","Traspaso"]);  // Creates an 'operation' column to define the type of operation (sale, rent, transfer)
            $table->enum('contact_type', ["Propietarios","Broker"]);  // Creates a 'contact_type' column to store the contact type (owners or brokers)
            $table->string('contact', 255);  // Creates a 'contact' column to store the contact's name (up to 255 characters)
            $table->text('contact_data');  // Creates a 'contact_data' column to store additional contact information
            $table->decimal('comission', 5, 2);  // Creates a 'comission' column to store the commission amount (up to 5 digits with 2 decimals)
            $table->enum('airbnb_rent', ["Si","No"]);  // Creates an 'airbnb_rent' column to indicate if the property is available for rent on Airbnb
            $table->decimal('price_m2', 10, 2)->nullable();  // Creates a 'price_m2' column to store the price per square meter (nullable)
            $table->text('amenities')->nullable();  // Creates an 'amenities' column to store a list of amenities (nullable)
            $table->text('maps')->nullable();  // Creates a 'maps' column to store map-related data (nullable)
            $table->text('content')->nullable();  // Creates a 'content' column to store additional content (nullable)
            $table->text('pdf')->nullable();  // Creates a 'pdf' column to store PDF files related to the property (nullable)
            $table->boolean('status');  // Creates a 'status' column to indicate whether the property is active or not
            $table->timestamps();  // Creates 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the 'properties' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');  // Drops the 'properties' table if it exists
    }
};