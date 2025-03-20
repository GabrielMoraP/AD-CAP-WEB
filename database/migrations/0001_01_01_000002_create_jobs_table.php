<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method creates the necessary tables for managing jobs and job batches.
     */
    public function up(): void
    {
        // Creating the 'jobs' table
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column as the primary key
            $table->string('queue')->index();  // Creates a 'queue' column and adds an index for faster querying
            $table->longText('payload');  // Creates a 'payload' column to store the job's data
            $table->unsignedTinyInteger('attempts');  // Creates an 'attempts' column to store the number of attempts for the job
            $table->unsignedInteger('reserved_at')->nullable();  // Creates a 'reserved_at' column to store when the job was reserved (nullable)
            $table->unsignedInteger('available_at');  // Creates an 'available_at' column to store when the job becomes available
            $table->unsignedInteger('created_at');  // Creates a 'created_at' column to store when the job was created
        });

        // Creating the 'job_batches' table
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();  // The 'id' column is the primary key for this table
            $table->string('name');  // Creates a 'name' column for the name of the job batch
            $table->integer('total_jobs');  // Creates a 'total_jobs' column to store the total number of jobs in the batch
            $table->integer('pending_jobs');  // Creates a 'pending_jobs' column to store the number of jobs still pending
            $table->integer('failed_jobs');  // Creates a 'failed_jobs' column to store the number of failed jobs
            $table->longText('failed_job_ids');  // Creates a 'failed_job_ids' column to store IDs of failed jobs
            $table->mediumText('options')->nullable();  // Creates an 'options' column to store additional options for the batch (nullable)
            $table->integer('cancelled_at')->nullable();  // Creates a 'cancelled_at' column to store when the batch was cancelled (nullable)
            $table->integer('created_at');  // Creates a 'created_at' column to store when the batch was created
            $table->integer('finished_at')->nullable();  // Creates a 'finished_at' column to store when the batch was finished (nullable)
        });

        // Creating the 'failed_jobs' table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();  // Creates an auto-incrementing 'id' column as the primary key
            $table->string('uuid')->unique();  // Creates a 'uuid' column for uniquely identifying failed jobs
            $table->text('connection');  // Creates a 'connection' column to store the connection type for the failed job
            $table->text('queue');  // Creates a 'queue' column to store the name of the queue the job was in
            $table->longText('payload');  // Creates a 'payload' column to store the data of the failed job
            $table->longText('exception');  // Creates an 'exception' column to store the exception details for the failed job
            $table->timestamp('failed_at')->useCurrent();  // Creates a 'failed_at' column to store when the job failed, using the current timestamp by default
        });
    }

    /**
     * Reverse the migrations.
     * This method drops the tables if they exist.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');  // Drops the 'jobs' table if it exists
        Schema::dropIfExists('job_batches');  // Drops the 'job_batches' table if it exists
        Schema::dropIfExists('failed_jobs');  // Drops the 'failed_jobs' table if it exists
    }
};