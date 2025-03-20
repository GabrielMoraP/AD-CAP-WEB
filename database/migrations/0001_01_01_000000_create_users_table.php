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
        // Creating the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column
            $table->string('name');  // Creates a 'name' column to store the user's name
            $table->string('user')->unique();  // Creates a 'user' column and ensures it's unique
            $table->string('password');  // Creates a 'password' column to store the user's password
            $table->enum('role', ["Administrador","Editor","Consultor"]);  // Creates a 'role' column with a set of predefined values
            $table->unsignedTinyInteger('avatar')->default(1);  // Creates an 'avatar' column with a default value of 1
            $table->rememberToken();  // Creates a column to store the "remember me" token
            $table->timestamps();  // Creates 'created_at' and 'updated_at' timestamp columns
        });

        // Creating the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('user')->primary();  // The 'user' column is the primary key
            $table->string('token');  // Creates a 'token' column for password reset tokens
            $table->timestamp('created_at')->nullable();  // Creates a 'created_at' column to store when the token was created
        });

        // Creating the 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();  // The 'id' column is the primary key
            $table->foreignId('user_id')->nullable()->index();  // Creates a 'user_id' column with a foreign key relationship and indexing
            $table->string('ip_address', 45)->nullable();  // Creates an 'ip_address' column to store the user's IP address
            $table->text('user_agent')->nullable();  // Creates a 'user_agent' column to store the user's browser information
            $table->longText('payload');  // Creates a 'payload' column to store session data
            $table->integer('last_activity')->index();  // Creates a 'last_activity' column and indexes it
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the tables if they exist.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');  // Drops the 'users' table if it exists
        Schema::dropIfExists('password_reset_tokens');  // Drops the 'password_reset_tokens' table if it exists
        Schema::dropIfExists('sessions');  // Drops the 'sessions' table if it exists
    }
};