<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method modifies the 'users' table by adding new columns.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme')->nullable()->default('default');  // Adds a 'theme' column to store the theme for the user, with a default value of 'default'
            $table->string('theme_color')->nullable();  // Adds a 'theme_color' column to store the theme color for the user (nullable)
        });
    }

    /**
     * Reverse the migrations.
     * This method removes the 'theme' and 'theme_color' columns from the 'users' table.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme', 'theme_color']);  // Drops the 'theme' and 'theme_color' columns from the 'users' table
        });
    }
};