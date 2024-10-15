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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role')->nullable();
            $table->string('school_name')->nullable();
            $table->string('activity_type');
            $table->text('description');
            $table->string('name')->nullable(); // Add this if you want to log team name separately
            $table->string('sport_category')->nullable(); // Add this if you want to log sport category separately
            $table->timestamps();
            
            // Foreign key relationship to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
