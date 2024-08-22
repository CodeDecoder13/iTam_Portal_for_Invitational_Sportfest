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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade'); // Adding the coach_id foreign key
            $table->string('jersey_no', 10);
            $table->string('first_name', 255);
            $table->string('middle_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->date('birthday')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('birth_certificate')->nullable();
            $table->integer('birth_certificate_status')->default(0);
            $table->string('parental_consent')->nullable();
            $table->integer('parental_consent_status')->default(0);
            $table->timestamp('last_update')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
