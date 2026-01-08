<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('athletes', function (Blueprint $table) {
            // Personal & Academic Information
            $table->integer('age')->nullable()->after('full_name');
            $table->string('academic_course', 255)->nullable()->after('gender');
            
            // Competition & Accomplishments
            $table->enum('highest_competition_level', [
                'Intramurals', 'University', 'Local', 'Regional', 'National', 'International'
            ])->nullable()->after('academic_course');
            $table->text('highest_accomplishment')->nullable()->after('highest_competition_level');
            $table->string('international_competition_name', 255)->nullable()->after('highest_accomplishment');
            
            // Training & Seminars
            $table->boolean('training_seminars_regional')->default(false)->after('international_competition_name');
            $table->boolean('training_seminars_national')->default(false)->after('training_seminars_regional');
            $table->boolean('training_seminars_international')->default(false)->after('training_seminars_national');
            $table->integer('training_frequency_days')->nullable()->after('training_seminars_international');
            $table->decimal('training_hours_per_day', 4, 2)->nullable()->after('training_frequency_days');
            
            // Scholarship & Benefits
            $table->enum('scholarship_status', [
                'Full Scholarship', 'Partial Scholarship', 'Non-scholar'
            ])->nullable()->after('training_hours_per_day');
            $table->decimal('monthly_living_allowance', 10, 2)->nullable()->after('scholarship_status');
            $table->boolean('board_lodging_support')->default(false)->after('monthly_living_allowance');
            $table->boolean('medical_insurance_support')->default(false)->after('board_lodging_support');
            $table->boolean('training_uniforms_support')->default(false)->after('medical_insurance_support');
            $table->decimal('average_tournament_allowance', 10, 2)->nullable()->after('training_uniforms_support');
            $table->boolean('playing_uniforms_sponsorship')->default(false)->after('average_tournament_allowance');
            $table->boolean('playing_gears_sponsorship')->default(false)->after('playing_uniforms_sponsorship');
            
            // Academic Support
            $table->boolean('excused_from_academic_obligations')->default(false)->after('playing_gears_sponsorship');
            $table->boolean('flexible_academic_schedule')->default(false)->after('excused_from_academic_obligations');
            $table->boolean('academic_tutorials_support')->default(false)->after('flexible_academic_schedule');
        });

        // Optional: Create competition levels table for better data management
        Schema::create('competition_levels', function (Blueprint $table) {
            $table->id('level_id');
            $table->string('level_name', 100);
            $table->text('description')->nullable();
            $table->integer('removed')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'academic_course',
                'highest_competition_level',
                'highest_accomplishment',
                'international_competition_name',
                'training_seminars_regional',
                'training_seminars_national',
                'training_seminars_international',
                'training_frequency_days',
                'training_hours_per_day',
                'scholarship_status',
                'monthly_living_allowance',
                'board_lodging_support',
                'medical_insurance_support',
                'training_uniforms_support',
                'average_tournament_allowance',
                'playing_uniforms_sponsorship',
                'playing_gears_sponsorship',
                'excused_from_academic_obligations',
                'flexible_academic_schedule',
                'academic_tutorials_support'
            ]);
        });

        Schema::dropIfExists('competition_levels');
    }
};