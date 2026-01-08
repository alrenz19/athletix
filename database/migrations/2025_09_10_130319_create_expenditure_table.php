<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_expenditures', function (Blueprint $table) {
            $table->id();
            
            // User and HEI ownership
            $table->unsignedBigInteger('user_id');
            $table->string('hei_code')->nullable();
            $table->string('hei_name');
            
            // E1: Fund Sources
            $table->decimal('athletic_fee_per_student', 10, 2)->default(0);
            $table->decimal('collection_athletic_fees', 10, 2)->default(0);
            $table->decimal('collection_donors', 10, 2)->default(0);
            $table->decimal('fundraising_income', 10, 2)->default(0);
            $table->decimal('local_govt_funding', 10, 2)->default(0);
            $table->decimal('national_govt_funding', 10, 2)->default(0);
            $table->decimal('other_sources', 10, 2)->default(0);
            
            // E2: Expenditures - Student Athletes
            $table->decimal('scholarships_male', 10, 2)->default(0);
            $table->decimal('scholarships_female', 10, 2)->default(0);
            $table->decimal('monthly_allowances', 10, 2)->default(0);
            $table->decimal('training_allowances', 10, 2)->default(0);
            $table->decimal('board_lodging', 10, 2)->default(0);
            $table->decimal('training_fees', 10, 2)->default(0);
            $table->decimal('medical_expenses', 10, 2)->default(0);
            $table->decimal('vitamins_medicines', 10, 2)->default(0);
            $table->decimal('other_athlete_expenses', 10, 2)->default(0);
            
            // E2: Expenditures - Personnel
            $table->decimal('salary_athletic_director', 10, 2)->default(0);
            $table->decimal('salary_head_coaches', 10, 2)->default(0);
            $table->decimal('salary_assistant_coaches', 10, 2)->default(0);
            $table->decimal('salary_trainers', 10, 2)->default(0);
            $table->decimal('salary_maintenance_staff', 10, 2)->default(0);
            $table->decimal('salary_other_personnel', 10, 2)->default(0);
            $table->decimal('personnel_uniforms', 10, 2)->default(0);
            $table->decimal('personnel_training', 10, 2)->default(0);
            $table->decimal('other_personnel_expenses', 10, 2)->default(0);
            
            // E2: Expenditures - Competitions
            $table->decimal('competition_fees', 10, 2)->default(0);
            $table->decimal('game_allowances_athletes', 10, 2)->default(0);
            $table->decimal('game_incentives_athletes', 10, 2)->default(0);
            $table->decimal('game_incentives_coaches', 10, 2)->default(0);
            $table->decimal('parade_uniforms', 10, 2)->default(0);
            $table->decimal('game_uniforms', 10, 2)->default(0);
            $table->decimal('honorarium_coaches', 10, 2)->default(0);
            $table->decimal('honorarium_officials', 10, 2)->default(0);
            $table->decimal('honorarium_staff', 10, 2)->default(0);
            $table->decimal('sports_equipment', 10, 2)->default(0);
            $table->decimal('board_lodging_competition', 10, 2)->default(0);
            $table->decimal('transportation_competition', 10, 2)->default(0);
            $table->decimal('first_aid_competition', 10, 2)->default(0);
            $table->decimal('association_membership', 10, 2)->default(0);
            $table->decimal('other_competition_expenses', 10, 2)->default(0);
            
            // E2: Expenditures - Intramurals
            $table->decimal('venue_rental_intramurals', 10, 2)->default(0);
            $table->decimal('uniforms_intramurals', 10, 2)->default(0);
            $table->decimal('honorarium_officials_intramurals', 10, 2)->default(0);
            $table->decimal('other_intramurals_expenses', 10, 2)->default(0);
            
            // E2: Expenditures - Facilities
            $table->decimal('facility_renovation', 10, 2)->default(0);
            $table->decimal('sports_equipment_acquisition', 10, 2)->default(0);
            $table->decimal('maintenance_supplies', 10, 2)->default(0);
            $table->decimal('other_facility_expenses', 10, 2)->default(0);
            
            // Academic Year and Metadata
            $table->string('academic_year')->default('2022-2023');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('hei_code');
            $table->index('academic_year');
            
            // Unique constraint - one budget per HEI per academic year
            $table->unique(['hei_code', 'academic_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_expenditures');
    }
};