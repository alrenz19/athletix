<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpenditure extends Model
{
    use HasFactory;

    protected $table = 'budget_expenditures';

    protected $fillable = [
        'user_id',
        'hei_code',
        'hei_name',
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
        'other_facility_expenses',
        'academic_year',
        'notes',
        'fund_sources', 'expenditures'
    ];

    protected $casts = [
        'fund_sources' => 'array',
        'expenditures' => 'array',
        // Add casts for decimal fields to handle null values
        'athletic_fee_per_student' => 'decimal:2',
        'collection_athletic_fees' => 'decimal:2',
        'collection_donors' => 'decimal:2',
        'fundraising_income' => 'decimal:2',
        'local_govt_funding' => 'decimal:2',
        'national_govt_funding' => 'decimal:2',
        'other_sources' => 'decimal:2',
        'scholarships_male' => 'decimal:2',
        'scholarships_female' => 'decimal:2',
        'monthly_allowances' => 'decimal:2',
        'training_allowances' => 'decimal:2',
        'board_lodging' => 'decimal:2',
        'training_fees' => 'decimal:2',
        'medical_expenses' => 'decimal:2',
        'vitamins_medicines' => 'decimal:2',
        'other_athlete_expenses' => 'decimal:2',
        'salary_athletic_director' => 'decimal:2',
        'salary_head_coaches' => 'decimal:2',
        'salary_assistant_coaches' => 'decimal:2',
        'salary_trainers' => 'decimal:2',
        'salary_maintenance_staff' => 'decimal:2',
        'salary_other_personnel' => 'decimal:2',
        'personnel_uniforms' => 'decimal:2',
        'personnel_training' => 'decimal:2',
        'other_personnel_expenses' => 'decimal:2',
        'competition_fees' => 'decimal:2',
        'game_allowances_athletes' => 'decimal:2',
        'game_incentives_athletes' => 'decimal:2',
        'game_incentives_coaches' => 'decimal:2',
        'parade_uniforms' => 'decimal:2',
        'game_uniforms' => 'decimal:2',
        'honorarium_coaches' => 'decimal:2',
        'honorarium_officials' => 'decimal:2',
        'honorarium_staff' => 'decimal:2',
        'sports_equipment' => 'decimal:2',
        'board_lodging_competition' => 'decimal:2',
        'transportation_competition' => 'decimal:2',
        'first_aid_competition' => 'decimal:2',
        'association_membership' => 'decimal:2',
        'other_competition_expenses' => 'decimal:2',
        'venue_rental_intramurals' => 'decimal:2',
        'uniforms_intramurals' => 'decimal:2',
        'honorarium_officials_intramurals' => 'decimal:2',
        'other_intramurals_expenses' => 'decimal:2',
        'facility_renovation' => 'decimal:2',
        'sports_equipment_acquisition' => 'decimal:2',
        'maintenance_supplies' => 'decimal:2',
        'other_facility_expenses' => 'decimal:2',
    ];

    // Add accessors to handle null values
    protected function getAthleticFeePerStudentAttribute($value)
    {
        return $value ?? 0;
    }

    protected function getCollectionAthleticFeesAttribute($value)
    {
        return $value ?? 0;
    }

    // Add similar accessors for all decimal fields if needed
    // Or handle null values in your application logic
}