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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id('athlete_id');
            $table->string('full_name')->index();
            $table->string('profile_url')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->enum('year_level', ['1st Year','2nd Year','3rd Year','4th Year','Alumni'])->default('1st Year');

            // FIX: Make ALL foreign keys nullable if using onDelete('set null')
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('sport_id')->nullable(); // Changed to nullable
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->enum('status', ['pending', 'in review','approved','reject', 'inactive'])->default('pending');
            $table->enum('conditions', ['active', 'injured','graduate'])->default('active');
            $table->string('school_id');
            $table->integer('removed')->default(0)->index();
            $table->timestamps();

            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('set null');
            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');

            $table->index('section_id');
            $table->index('sport_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};