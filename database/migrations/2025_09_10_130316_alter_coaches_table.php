<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coaches', function (Blueprint $table) {
            // Personal Information
            $table->integer('age')->nullable()->after('full_name');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('age');
            
            // Employment Information
            $table->string('current_position_title', 255)->nullable()->after('sport_id');
            $table->string('sports_program_position', 255)->nullable()->after('current_position_title');
            $table->enum('employment_status', ['Permanent', 'Contractual', 'Part-time', 'Volunteer'])->nullable()->after('sports_program_position');
            $table->decimal('monthly_salary', 12, 2)->nullable()->after('employment_status');
            $table->integer('years_experience')->nullable()->after('monthly_salary');
            
            // Athletic Background
            $table->boolean('was_previous_athlete')->default(false)->after('years_experience');
            $table->enum('highest_competition_level', [
                'Intramurals', 'University', 'Local', 'Regional', 'National', 'International'
            ])->nullable()->after('was_previous_athlete');
            $table->text('highest_accomplishment_athlete')->nullable()->after('highest_competition_level');
            $table->string('international_competition_athlete', 255)->nullable()->after('highest_accomplishment_athlete');
            
            // Coaching Accomplishments
            $table->text('highest_accomplishment_coach')->nullable()->after('international_competition_athlete');
            $table->string('international_competition_coach', 255)->nullable()->after('highest_accomplishment_coach');
            
            // Memberships & Licenses
            $table->boolean('regional_membership')->default(false)->after('international_competition_coach');
            $table->boolean('national_membership')->default(false)->after('regional_membership');
            $table->boolean('international_membership')->default(false)->after('national_membership');
            $table->string('international_membership_name', 255)->nullable()->after('international_membership');
            
            // Educational Background
            $table->enum('highest_degree', ['High School', 'Bachelor', 'Master', 'Doctorate'])->nullable()->after('international_membership_name');
            $table->string('bachelor_degree', 255)->nullable()->after('highest_degree');
            $table->string('master_degree', 255)->nullable()->after('bachelor_degree');
            $table->string('doctorate_degree', 255)->nullable()->after('master_degree');
        });
    }

    public function down()
    {
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'gender',
                'current_position_title',
                'sports_program_position',
                'employment_status',
                'monthly_salary',
                'years_experience',
                'was_previous_athlete',
                'highest_competition_level',
                'highest_accomplishment_athlete',
                'international_competition_athlete',
                'highest_accomplishment_coach',
                'international_competition_coach',
                'regional_membership',
                'national_membership',
                'international_membership',
                'international_membership_name',
                'highest_degree',
                'bachelor_degree',
                'master_degree',
                'doctorate_degree'
            ]);
        });
    }
};