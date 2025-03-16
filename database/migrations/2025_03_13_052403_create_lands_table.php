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
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ubication_id')->nullable()->constrained("ubications")->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained("zones")->nullOnDelete();
            $table->enum('classification', ["Residencial","Unifamiliar","Industrial","Comercial"]);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('currency', ["MDD","MDP"]);
            $table->decimal('area', 10, 2);
            $table->enum('view', ["Carretera","Mar","Selva","Ciudad","Costa"]);
            $table->enum('operation', ["Venta","Renta","Traspaso"]);
            $table->enum('contact_type', ["Propietarios","Broker"]);
            $table->string('contact', 255);
            $table->text('contact_data');
            $table->decimal('comission', 5, 2);
            $table->decimal('front', 10, 2)->nullable();
            $table->decimal('bottom', 10, 2)->nullable();
            $table->string('density', 255)->nullable();
            $table->string('soil', 255)->nullable();
            $table->text('maps')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('lands');
    }
};
