<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the 'lands' table.
     */
    public function up(): void
    {
        // Creating the 'lands' table
        Schema::create('lands', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column as the primary key
            $table->foreignId('ubication_id')->nullable()->constrained("ubications")->nullOnDelete();  // Foreign key referencing 'ubications', nullable, with a null on delete
            $table->foreignId('zone_id')->nullable()->constrained("zones")->nullOnDelete();  // Foreign key referencing 'zones', nullable, with a null on delete
            $table->enum('classification', ["Residencial","Unifamiliar","Industrial","Comercial"]);  // Creates a 'classification' column with predefined values for land classification
            $table->text('description');  // Creates a 'description' column to store a description of the land
            $table->decimal('price', 10, 2);  // Creates a 'price' column to store the land's price (up to 10 digits with 2 decimals)
            $table->enum('currency', ["MDD","MDP"]);  // Creates a 'currency' column with predefined values for the currency (MDD or MDP)
            $table->decimal('area', 10, 2);  // Creates an 'area' column to store the area of the land (in square meters)
            $table->enum('view', ["Carretera","Mar","Selva","Ciudad","Costa"]);  // Creates a 'view' column with predefined values for the land's view
            $table->enum('operation', ["Venta","Renta","Traspaso"]);  // Creates an 'operation' column to define the type of operation (sale, rent, transfer)
            $table->enum('contact_type', ["Propietarios","Broker"]);  // Creates a 'contact_type' column to store the contact type (owners or brokers)
            $table->string('contact', 255);  // Creates a 'contact' column to store the contact's name (up to 255 characters)
            $table->text('contact_data');  // Creates a 'contact_data' column to store additional contact information
            $table->decimal('comission', 5, 2);  // Creates a 'comission' column to store the commission amount (up to 5 digits with 2 decimals)
            $table->decimal('front', 10, 2)->nullable();  // Creates a 'front' column to store the front measurement of the land (nullable)
            $table->decimal('bottom', 10, 2)->nullable();  // Creates a 'bottom' column to store the bottom measurement of the land (nullable)
            $table->string('density', 255)->nullable();  // Creates a 'density' column to store the land's density (nullable)
            $table->string('soil', 255)->nullable();  // Creates a 'soil' column to store the soil type (nullable)
            $table->text('maps')->nullable();  // Creates a 'maps' column to store map-related data (nullable)
            $table->text('content')->nullable();  // Creates a 'content' column to store additional content (nullable)
            $table->text('pdf')->nullable();  // Creates a 'pdf' column to store PDF files related to the land (nullable)
            $table->boolean('status');  // Creates a 'status' column to indicate whether the land is active or not
            $table->timestamps();  // Creates 'created_at' and 'updated_at' timestamp columns
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the 'lands' table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('lands');  // Drops the 'lands' table if it exists
    }
};