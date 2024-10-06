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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('sport_category');
            $table->foreignId('team1_id')->constrained('teams'); // Foreign key for Team 1
            $table->foreignId('team2_id')->constrained('teams'); // Foreign key for Team 2
            $table->integer('team1_score')->default(0); // Add team1_score column with default 0
            $table->integer('team2_score')->default(0); // Add team2_score column with default 0
            $table->date('game_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('comments_count')->default(0); // Add comments_count column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
