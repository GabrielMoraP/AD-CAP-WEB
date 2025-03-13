<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ubication_id')->nullable()->constrained("ubications")->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained("zones")->nullOnDelete();
            $table->string('development', 255);
            $table->enum('classification', ["Lujo","Premium","Gama"]);
            $table->enum('type', ["Casa","Departamento","Oficina","Local","Hotel","Bodega","Penthouse"]);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('currency', ["MDD","MDP"]);
            $table->decimal('area_m2', 10, 2);
            $table->decimal('contruction_m2', 10, 2);
            $table->decimal('price_m2', 10, 2);
            $table->unsignedInteger('rooms');
            $table->unsignedInteger('bathrooms');
            $table->text('amenities');
            $table->enum('pet_friendly', ["Si","No"]);
            $table->enum('family', ["Infantes","Pareja-Mayor","Pareja-Joven","Familiar","Una-Persona","Negocio"]);
            $table->enum('view', ["Carretera","Mar","Selva","Ciudad","Costa"]);
            $table->enum('operation', ["Venta","Renta","Traspaso"]);
            $table->string('contact', 255);
            $table->enum('contact_type', ["Propietarios","Broker"]);
            $table->text('contact_data');
            $table->decimal('comission', 5, 2);
            $table->text('maps');
            $table->enum('airbnb_rent', ["Si","No"]);
            $table->text('content');
            $table->text('pdf')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
