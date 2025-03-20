<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the necessary tables.
     */
    public function up(): void
    {
        // Creating the 'cache' table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();  // The 'key' column is the primary key for this table
            $table->mediumText('value');  // Creates a 'value' column to store the cached data
            $table->integer('expiration');  // Creates an 'expiration' column to store the cache expiration time
        });

        // Creating the 'cache_locks' table
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();  // The 'key' column is the primary key for this table
            $table->string('owner');  // Creates an 'owner' column to store the owner of the cache lock
            $table->integer('expiration');  // Creates an 'expiration' column to store the cache lock expiration time
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the tables if they exist.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');  // Drops the 'cache' table if it exists
        Schema::dropIfExists('cache_locks');  // Drops the 'cache_locks' table if it exists
    }
};
