<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('sport_category');
            $table->integer('wins')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('points')->default(0);
            $table->integer('rank')->nullable();
            $table->timestamps();
            
            // Composite unique index
            $table->unique(['team_id', 'sport_category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
