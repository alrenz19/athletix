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
        Schema::create('feedback_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('school_id');
            $table->string('username')->unique();
            $table->string('password'); // hashed
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('sports_programs', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->unsignedBigInteger('sport_id');
            $table->string('category', 20)->nullable();
            $table->string('assoc_1', 10)->nullable();
            $table->string('assoc_2', 10)->nullable();
            $table->string('assoc_3a', 10)->nullable();
            $table->string('assoc_3b', 10)->nullable();
            $table->text('assoc_other')->nullable();
            $table->string('league_active_1', 10)->nullable();
            $table->integer('league_count_1')->nullable();
            $table->string('league_active_2', 10)->nullable();
            $table->integer('league_count_2')->nullable();
            $table->string('league_active_3', 10)->nullable();
            $table->integer('league_count_3')->nullable();
            $table->string('league_active_4', 10)->nullable();
            $table->integer('league_count_4')->nullable();
            $table->string('league_active_5', 10)->nullable();
            $table->integer('league_count_5')->nullable();
            $table->string('well_1', 10)->nullable();
            $table->string('well_2', 10)->nullable();
            $table->string('well_3', 10)->nullable();
            $table->string('cpd_1', 10)->nullable();
            $table->string('cpd_2', 10)->nullable();
            $table->string('cpd_3', 10)->nullable();
            $table->string('cpd_4', 10)->nullable();
            $table->string('cpd_5', 10)->nullable();
            $table->string('cpd_6', 10)->nullable();
            $table->string('cpd_7', 10)->nullable();
            $table->timestamps(); // creates `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_data');
    }
};
