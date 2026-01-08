<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\FormAExport;
use App\Exports\FormBExport;
use App\Exports\FormCExport;
use App\Exports\FormDExport;
use App\Exports\FormEExport;
use App\Exports\FormFExport;
use App\Exports\FormGExport;
use App\Models\InstitutionalInformation;
use App\Models\Sport;
use App\Models\Athlete;
use App\Models\Coach;
use App\Models\SportsProgram;
use App\Models\BudgetExpenditure;
use App\Models\Feedback;
use App\Models\NonVarsityClub;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        $year = $request->get('year');
        $yearLevel = $request->get('year_level');
        $sport = $request->get('sport');
        $status = $request->get('status');

        // Get sports for filter dropdown and sport selection
        $sports = Sport::all();

        // Get athletes with filters
        $athletesQuery = Athlete::with(['sport']);
        
        if ($year) $athletesQuery->where('academic_year', $year);
        if ($yearLevel) $athletesQuery->where('year_level', $yearLevel);
        if ($sport) $athletesQuery->where('sport_id', $sport);
        if ($status) $athletesQuery->where('status', $status);
        
        $athletes = $athletesQuery->get();

        // Get coaches
        $coaches = Coach::with(['sport'])->get();

        // Get institutional data
        $institutionalData = InstitutionalInformation::first() ?? new InstitutionalInformation();

        // Fetch ALL sports programs from DB (remove toArray() to keep as Collection)
        $sportsPrograms = SportsProgram::with('sport')->get();

        // Get budget and feedback
        $budgetData = BudgetExpenditure::first() ?? new BudgetExpenditure();
        $feedbackData = Feedback::first() ?? new Feedback();

        // Get non-varsity clubs
        $nonVarsityClubs = Sport::where('sport_type', 'non_varsity')->get();

        return view('reports.index', compact(
            'athletes',
            'coaches',
            'sports',
            'institutionalData',
            'sportsPrograms', // This is now a Collection, not an array
            'budgetData',
            'feedbackData',
            'nonVarsityClubs'
        ));
    }

/**
 * Generate sports programs data from athletes information
 */
    private function getSportsProgramsFromAthletes($athletes)
    {
        $sportsData = [];
        
        // Convert to collection for grouping
        $athletesCollection = collect($athletes);
        
        // Group athletes by sport_id
        $athletesBySport = $athletesCollection->groupBy('sport_id');
        
        foreach ($athletesBySport as $sportId => $sportAthletes) {
            $sport = $sportAthletes->first()['sport'] ?? [];
            
            // Count by gender
            $maleCount = $sportAthletes->where('gender', 'Male')->count();
            $femaleCount = $sportAthletes->where('gender', 'Female')->count();
            
            // Determine category based on gender distribution
            if ($maleCount > 0 && $femaleCount > 0) {
                $category = 'Mixed';
            } elseif ($maleCount > 0) {
                $category = 'Men';
            } else {
                $category = 'Women';
            }
            
            // Get training information from athletes
            $trainingDays = $sportAthletes->avg('training_frequency_days');
            $trainingHours = $sportAthletes->avg('training_hours_per_day');
            
            // Count athletes with different training levels
            $regionalTraining = $sportAthletes->where('training_seminars_regional', true)->count();
            $nationalTraining = $sportAthletes->where('training_seminars_national', true)->count();
            $internationalTraining = $sportAthletes->where('training_seminars_international', true)->count();
            
            // Count competition levels
            $competitionLevels = $sportAthletes->groupBy('highest_competition_level')->map->count()->toArray();
            
            $sportsData[$sportId] = [
                'sport' => $sport,
                'category' => $category,
                'total_athletes' => $sportAthletes->count(),
                'male_count' => $maleCount,
                'female_count' => $femaleCount,
                'avg_training_days' => round($trainingDays, 1),
                'avg_training_hours' => round($trainingHours, 1),
                'regional_training_count' => $regionalTraining,
                'national_training_count' => $nationalTraining,
                'international_training_count' => $internationalTraining,
                'competition_levels' => $competitionLevels,
                'scholarship_count' => $sportAthletes->where('scholarship_status', '!=', 'Non-scholar')->count(),
            ];
        }
        
        return $sportsData;
    }

    public function saveInstitutional(Request $request)
    {
        try {
            $data = $request->validate([
                'hei_name' => 'required|string|max:255',
                'hei_campus' => 'nullable|string|max:255',
                'hei_address' => 'required|string',
                'hei_president' => 'required|string|max:255',
                'sports_director_name' => 'required|string|max:255',
                'sports_director_email' => 'required|email',
                'sports_director_alt_email' => 'nullable|email',
                'sports_director_mobile' => 'required|string|max:20',
                'contact_person_name' => 'required|string|max:255',
                'contact_person_email' => 'required|email',
                'contact_person_alt_email' => 'nullable|email',
                'contact_person_mobile' => 'required|string|max:20',
                'departmental_intramurals' => 'nullable|string|max:10',
                'interdepartmental_intramurals' => 'nullable|string|max:10',
                'intercampus_intramurals' => 'nullable|string|max:10',
                'facilities.gymnasium' => 'nullable|string|max:10',
                'facilities.multipurpose_hall' => 'nullable|string|max:10',
                'facilities.quadrangle' => 'nullable|string|max:10',
                'facilities.athletes_dormitory' => 'nullable|string|max:10',
                'facilities.swimming_pool' => 'nullable|string|max:10',
                'facilities.track_oval' => 'nullable|string|max:10',
                'facilities.dance_studio' => 'nullable|string|max:10',
                'facilities.tennis_court' => 'nullable|string|max:10',
                'facilities.football_field' => 'nullable|string|max:10',
                'facilities.boxing_ring' => 'nullable|string|max:10',
                'facilities.sports_medical_facility' => 'nullable|string|max:10',
                'other_facilities' => 'nullable|string',
            ]);
            $data['user_id'] = auth()->id();
            $institutionalData = InstitutionalInformation::firstOrNew([]);
            $institutionalData->fill($data);
            $institutionalData->save();

            return back()->with('success', 'Institutional data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save institutional data: ' . $e->getMessage());
        }
    }

    public function saveSportsPrograms(Request $request)
    {
        try {
            $sportsData = $request->input('sports', []);
            $newSportsData = $request->input('new_sports', []);

            foreach ($sportsData as $sportId => $data) {
                SportsProgram::updateOrCreate(
                    ['sport_id' => $sportId],
                    $data
                );
            }

            foreach ($newSportsData as $data) {
                if (!empty($data['sport_id'])) {
                    SportsProgram::create($data);
                }
            }

            return back()->with('success', 'Sports programs data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save sports programs data: ' . $e->getMessage());
        }
    }

    public function saveStudentAthletes(Request $request)
    {
        try {
            $athletesData = $request->input('athletes', []);
            $newAthletesData = $request->input('new_athletes', []);

            foreach ($athletesData as $athleteId => $data) {
                $athlete = Athlete::find($athleteId);
                if ($athlete) {
                    $athlete->update($data);
                }
            }

            foreach ($newAthletesData as $data) {
                if (!empty($data['full_name'])) {
                    Athlete::create($data);
                }
            }

            return back()->with('success', 'Student-athletes data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save student-athletes data: ' . $e->getMessage());
        }
    }

    public function saveSportsPersonnel(Request $request)
    {
        try {
            $personnelData = $request->input('personnel', []);
            $newPersonnelData = $request->input('new_personnel', []);

            foreach ($personnelData as $coachId => $data) {
                $coach = Coach::find($coachId);
                if ($coach) {
                    $coach->update($data);
                }
            }

            foreach ($newPersonnelData as $data) {
                if (!empty($data['full_name'])) {
                    Coach::create($data);
                }
            }

            return back()->with('success', 'Sports personnel data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save sports personnel data: ' . $e->getMessage());
        }
    }

    public function saveBudgetExpenditure(Request $request)
    {
        try {
            $data = $request->validate([
                'fund_sources' => 'nullable|array',
                'expenditures' => 'nullable|array',
            ]);

            $budgetData = BudgetExpenditure::firstOrNew([]);
            
            // Set default values for institutional data
            $budgetData->hei_name = 'Cebu Technological University - Main Campus';
            $budgetData->academic_year = '2022-2023';
            $budgetData->user_id = auth()->id();
            
            // Map fund_sources array to individual columns
            if (isset($data['fund_sources'])) {
                foreach ($data['fund_sources'] as $key => $value) {
                    if (in_array($key, $this->getFundSourceColumns())) {
                        $budgetData->$key = $value ?: 0;
                    }
                }
            }
            
            // Map expenditures array to individual columns
            if (isset($data['expenditures'])) {
                foreach ($data['expenditures'] as $key => $value) {
                    if (in_array($key, $this->getExpenditureColumns())) {
                        $budgetData->$key = $value ?: 0;
                    }
                }
            }
            
            $budgetData->save();

            return back()->with('success', 'Budget and expenditure data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to save budget data: ' . $e->getMessage());
        }
    }

    // Helper methods to get column lists
    private function getFundSourceColumns()
    {
        return [
            'athletic_fee_per_student',
            'collection_athletic_fees',
            'collection_donors',
            'fundraising_income',
            'local_govt_funding',
            'national_govt_funding',
            'other_sources'
        ];
    }

    private function getExpenditureColumns()
    {
        return [
            // Student Athletes
            'scholarships_male',
            'scholarships_female',
            'monthly_allowances',
            'training_allowances',
            'board_lodging',
            'training_fees',
            'medical_expenses',
            'vitamins_medicines',
            'other_athlete_expenses',
            // Personnel
            'salary_athletic_director',
            'salary_head_coaches',
            'salary_assistant_coaches',
            'salary_trainers',
            'salary_maintenance_staff',
            'salary_other_personnel',
            'personnel_uniforms',
            'personnel_training',
            'other_personnel_expenses',
            // Competitions
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
            // Intramurals
            'venue_rental_intramurals',
            'uniforms_intramurals',
            'honorarium_officials_intramurals',
            'other_intramurals_expenses',
            // Facilities
            'facility_renovation',
            'sports_equipment_acquisition',
            'maintenance_supplies',
            'other_facility_expenses'
        ];
    }

    public function saveFeedback(Request $request)
    {
        try {
            $data = $request->validate([
                'template_improvements' => 'nullable|string',
                'additional_data' => 'nullable|string',
                'difficult_data' => 'nullable|string',
                'easy_data' => 'nullable|string',
                'additional_comments' => 'nullable|string',
                'respondent_name' => 'nullable|string|max:255',
                'respondent_email' => 'nullable|email',
            ]);

            $feedbackData = Feedback::firstOrNew([]);
            $feedbackData->fill($data);
            $feedbackData->save();

            return back()->with('success', 'Feedback submitted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit feedback: ' . $e->getMessage());
        }
    }

    public function exportForm($form, $format)
    {
        try {
            $institutionalData = InstitutionalInformation::first() ?? new InstitutionalInformation();
            $timestamp = now()->format('Y-m-d_His');
            
            switch (strtoupper($form)) {
                case 'A':
                    return $this->exportFormA($format);
                    break;
                    
                case 'B':
                    return $this->exportFormB($format);
                    break;
                    
                case 'C':
                   return $this->exportFormC($format);
                    break;
                    
                case 'D':
                    return $this->exportFormD($format);
                    break;
                    
                case 'E':
                    return $this->exportFormE($format);
                    break;
                    
                case 'F':
                    return $this->exportFormF($format);
                    break;
                    
                case 'G':
                    return $this->exportFormG($format);
                    break;
                    
                default:
                    return back()->with('error', 'Invalid form specified');
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function exportFormG($format)
    {
        // Fetch feedback data
        $feedbackData = Feedback::first()?->toArray() ?? [];

        if ($format === 'xlsx') {
            return Excel::download(new FormGExport($feedbackData), 'CHED_FormG.xlsx');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.formG-pdf', compact('feedbackData'))
                    ->setPaper('A4', 'portrait');
            return $pdf->download('CHED_FormG.pdf');
        }
        abort(404);
    }

    public function exportFormF($format)
    {
        // Fetch non-varsity clubs from the sports table
        $nonVarsityClubs = Sport::where('sport_type', 'non_varsity')->get();

        if ($format === 'xlsx') {
            return Excel::download(new FormFExport($nonVarsityClubs->toArray()), 'CHED_FormF.xlsx');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.formF-pdf', compact('nonVarsityClubs'))
                    ->setPaper('A4', 'landscape');
            return $pdf->download('CHED_FormF.pdf');
        }

        abort(404);
    }

    public function exportFormE($format)
    {
        $budget = BudgetExpenditure::first();
        $hei_name = 'Cebu Technological University - Main Campus';

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.formE-pdf', [
                'budget' => $budget ? $budget->toArray() : [],
                'hei_name' => $hei_name
            ])->setPaper('A4', 'landscape');
            return $pdf->download('CHED_FormE.pdf');
        }

        if ($format === 'xlsx') {
            return Excel::download(new FormEExport($budget ? $budget->toArray() : []), 'CHED_FormE.xlsx');
        }

        abort(404);
    }

    public function exportFormD($format)
    {
        $coaches = Coach::with('sport')->get()->toArray();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.formD-pdf', compact('coaches'))
                    ->setPaper('A4', 'landscape');
            return $pdf->download('CHED_FormD.pdf');
        }

        if ($format === 'xlsx') {
            return Excel::download(new FormDExport($coaches), 'CHED_FormD.' . $format);
        }

        abort(404);
    }

    public function exportFormA($format)
    {
        // Fetch the first record (or modify to fetch a specific record)
        $data = InstitutionalInformation::first()?->toArray() ?? [];

        $facilities = $data['facilities'] ?? [];

        if ($format == 'xlsx') {
            return Excel::download(new FormAExport($data), 'CHED_FormA.xlsx');
        }

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('reports.formA-pdf', compact('data','facilities'))
                    ->setPaper('A4', 'landscape'); // Landscape to fit table
            return $pdf->download('CHED_FormA.pdf');
        }

        abort(404);
    }

    public function exportFormB($format)
    {
        $hei_name = 'Cebu Technological University - Main Campus';

        // Fetch all sports
        $sports = Sport::all();

        if ($format === 'pdf') {
            $sportsPrograms = SportsProgram::all()->groupBy('sport_id');
            return Pdf::loadView('reports.formB-pdf', compact('hei_name', 'sports', 'sportsPrograms'))
                    ->setPaper('A4', 'landscape')
                    ->download('CHED_FormB.pdf');
        }

        if ($format === 'xlsx') {
            $sportsPrograms = SportsProgram::all()->map(function($sp) use ($sports) {
            $sport = $sports->firstWhere('id', $sp->sport_id);
            return [
                'sport_code' => $sport->sport_code ?? null,
                'sport_name' => $sport->sport_name ?? null,
                'category' => $sp->category ?? null,
                'assoc_1' => $sp->assoc_1 ?? null,
                'assoc_2' => $sp->assoc_2 ?? null,
                'assoc_3a' => $sp->assoc_3a ?? null,
                'assoc_3b' => $sp->assoc_3b ?? null,
                'assoc_other' => $sp->assoc_other ?? null,
                'league_active_1' => $sp->league_active_1 ?? null,
                'league_count_1' => $sp->league_count_1 ?? null,
                'league_active_2' => $sp->league_active_2 ?? null,
                'league_count_2' => $sp->league_count_2 ?? null,
                'league_active_3' => $sp->league_active_3 ?? null,
                'league_count_3' => $sp->league_count_3 ?? null,
                'league_active_4' => $sp->league_active_4 ?? null,
                'league_count_4' => $sp->league_count_4 ?? null,
                'league_active_5' => $sp->league_active_5 ?? null,
                'league_count_5' => $sp->league_count_5 ?? null,
                'well_1' => $sp->well_1 ?? null,
                'well_2' => $sp->well_2 ?? null,
                'well_3' => $sp->well_3 ?? null,
                'cpd_1' => $sp->cpd_1 ?? null,
                'cpd_2' => $sp->cpd_2 ?? null,
                'cpd_3' => $sp->cpd_3 ?? null,
                'cpd_4' => $sp->cpd_4 ?? null,
                'cpd_5' => $sp->cpd_5 ?? null,
                'cpd_6' => $sp->cpd_6 ?? null,
                'cpd_7' => $sp->cpd_7 ?? null,
            ];
        })->toArray();

        return Excel::download(new FormBExport($sportsPrograms), 'CHED_FormB.xlsx');
        }

        abort(404);
    }

    public function exportFormC($format)
    {
        $hei_name = 'Cebu Technological University - Main Campus';

        // Fetch all athletes with their sport relationship
        $athletes = Athlete::with('sport')->get();

        if ($format === 'pdf') {
            return Pdf::loadView('reports.formC-pdf', compact('hei_name', 'athletes'))
                    ->setPaper('A4', 'landscape')
                    ->download('CHED_FormC.pdf');
        }

        if ($format === 'xlsx') {
            $athletesData = $athletes->map(function($athlete) {
                return [
                    'full_name' => $athlete->full_name,
                    'age' => $athlete->age,
                    'sport_name' => optional($athlete->sport)->sport_name,
                    'gender' => ucfirst($athlete->gender),
                    'academic_course' => $athlete->academic_course,
                    'highest_competition_level' => $athlete->highest_competition_level,
                    'highest_accomplishment' => $athlete->highest_accomplishment,
                    'international_competition_name' => $athlete->international_competition_name,
                    'training_seminars_regional' => $athlete->training_seminars_regional ? 'Yes' : 'No',
                    'training_seminars_national' => $athlete->training_seminars_national ? 'Yes' : 'No',
                    'training_seminars_international' => $athlete->training_seminars_international ? 'Yes' : 'No',
                    'training_frequency_days' => $athlete->training_frequency_days,
                    'training_hours_per_day' => $athlete->training_hours_per_day,
                    'scholarship_status' => $athlete->scholarship_status,
                    'monthly_living_allowance' => $athlete->monthly_living_allowance,
                    'board_lodging_support' => $athlete->board_lodging_support,
                    'medical_insurance_support' => $athlete->medical_insurance_support,
                    'training_uniforms_support' => $athlete->training_uniforms_support,
                    'average_tournament_allowance' => $athlete->average_tournament_allowance,
                    'playing_uniforms_sponsorship' => $athlete->playing_uniforms_sponsorship,
                    'playing_gears_sponsorship' => $athlete->playing_gears_sponsorship,
                    'excused_from_academic_obligations' => $athlete->excused_from_academic_obligations,
                    'flexible_academic_schedule' => $athlete->flexible_academic_schedule,
                    'academic_tutorials_support' => $athlete->academic_tutorials_support,
                ];
            })->toArray();

            return Excel::download(new FormCExport($athletesData), 'CHED_FormC.xlsx');
        }

        abort(404);
    }





    
}
