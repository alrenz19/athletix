<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budget_expenditures', function (Blueprint $table) {
            // Make all decimal columns nullable
            $decimalColumns = [
                'athletic_fee_per_student',
                'collection_athletic_fees',
                'collection_donors',
                'fundraising_income',
                'local_govt_funding',
                'national_govt_funding',
                'other_sources',
                'scholarships_male',
                'scholarships_female',
                'monthly_allowances',
                'training_allowances',
                'board_lodging',
                'training_fees',
                'medical_expenses',
                'vitamins_medicines',
                'other_athlete_expenses',
                'salary_athletic_director',
                'salary_head_coaches',
                'salary_assistant_coaches',
                'salary_trainers',
                'salary_maintenance_staff',
                'salary_other_personnel',
                'personnel_uniforms',
                'personnel_training',
                'other_personnel_expenses',
                'competition_fees',
                'game_allowances_athletes',
                'game_incentives_athletes',
                'game_incentives_coaches',
                'parade_uniforms',
                'game_uniforms',
                'honorarium_coaches',
                'honorarium_officials',
                'honorarium_staff',
                'sports_equipment',
                'board_lodging_competition',
                'transportation_competition',
                'first_aid_competition',
                'association_membership',
                'other_competition_expenses',
                'venue_rental_intramurals',
                'uniforms_intramurals',
                'honorarium_officials_intramurals',
                'other_intramurals_expenses',
                'facility_renovation',
                'sports_equipment_acquisition',
                'maintenance_supplies',
                'other_facility_expenses'
            ];

            foreach ($decimalColumns as $column) {
                $table->decimal($column, 10, 2)->nullable()->default(null)->change();
            }
        });
    }

    public function down()
    {
        Schema::table('budget_expenditures', function (Blueprint $table) {
            // Revert back to not nullable with default 0.00
            $decimalColumns = [
                'athletic_fee_per_student',
                'collection_athletic_fees',
                'collection_donors',
                'fundraising_income',
                'local_govt_funding',
                'national_govt_funding',
                'other_sources',
                'scholarships_male',
                'scholarships_female',
                'monthly_allowances',
                'training_allowances',
                'board_lodging',
                'training_fees',
                'medical_expenses',
                'vitamins_medicines',
                'other_athlete_expenses',
                'salary_athletic_director',
                'salary_head_coaches',
                'salary_assistant_coaches',
                'salary_trainers',
                'salary_maintenance_staff',
                'salary_other_personnel',
                'personnel_uniforms',
                'personnel_training',
                'other_personnel_expenses',
                'competition_fees',
                'game_allowances_athletes',
                'game_incentives_athletes',
                'game_incentives_coaches',
                'parade_uniforms',
                'game_uniforms',
                'honorarium_coaches',
                'honorarium_officials',
                'honorarium_staff',
                'sports_equipment',
                'board_lodging_competition',
                'transportation_competition',
                'first_aid_competition',
                'association_membership',
                'other_competition_expenses',
                'venue_rental_intramurals',
                'uniforms_intramurals',
                'honorarium_officials_intramurals',
                'other_intramurals_expenses',
                'facility_renovation',
                'sports_equipment_acquisition',
                'maintenance_supplies',
                'other_facility_expenses'
            ];

            foreach ($decimalColumns as $column) {
                $table->decimal($column, 10, 2)->nullable(false)->default(0.00)->change();
            }
        });
    }
};