<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form E – Sports Budget and Expenditure</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .subtitle { text-align: center; font-style: italic; font-size: 12px; margin-bottom: 15px; }
        .logos { width: 100%; display: flex; justify-content: space-between; margin-bottom: 10px; }
        .logos img { height: 60px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 4px; text-align: left; }
        th.section { background-color: #dbeafe; text-align: center; font-weight: bold; }
        th.section2 { background-color: #dcfce7; text-align: center; font-weight: bold; }
        th.subsection { background-color: #e0e7ff; font-weight: bold; }
        .summary { background-color: #fef3c7; padding: 8px; border: 1px solid #d97706; margin-top: 10px; }
        .summary h4 { margin: 0 0 5px 0; font-weight: bold; }
        .amount { text-align: right; font-family: monospace; }
    </style>
</head>
<body>
    <div class="logos">
        <img src="{{ public_path('images/ctu-logo.png') }}" alt="CTU Logo">
        <img src="{{ public_path('images/ched-logo.png') }}" alt="CHED Logo">
    </div>

    <div class="header">{{ $hei_name }}</div>
    <div class="subtitle">Form E – Sports Budget and Expenditure</div>

    <!-- SECTION E1: Fund Sources -->
    <table>
        <thead>
            <tr>
                <th colspan="9" class="section">SECTION E1: FUND SOURCES</th>
            </tr>
            <tr>
                <th>ITEM CODE</th>
                <th>Athletic Fee Per Student Per Semester</th>
                <th>Collection from Athletic Fees/Miscellaneous</th>
                <th>Collection from Donors</th>
                <th>Fundraising and/or Income Generating Programs</th>
                <th>Funding Support from Local Government funds/allocations</th>
                <th>Funding Support from National Government funds/allocations</th>
                <th>Other Sources Not Included Above</th>
                <th>ESTIMATED AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>E-BUDGET-1</td>
                <td class="amount">{{ number_format($budget['fund_sources']['athletic_fee_per_student'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['collection_athletic_fees'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['collection_donors'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['fundraising_income'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['local_govt_funding'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['national_govt_funding'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['fund_sources']['other_sources'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format(array_sum($budget['fund_sources'] ?? []), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- SECTION E2: Expenditures -->
    <table>
        <thead>
            <tr>
                <th colspan="44" class="section2">SECTION E2: EXPENDITURES</th>
            </tr>
            <tr>
                <th>ITEM CODE</th>
                <!-- Student Athletes -->
                <th colspan="9" class="subsection">STUDENT-ATHLETES</th>
                <!-- Personnel -->
                <th colspan="9" class="subsection">PERSONNEL</th>
                <!-- Competitions -->
                <th colspan="15" class="subsection">COMPETITIONS</th>
                <!-- Intramurals -->
                <th colspan="4" class="subsection">INTRAMURALS</th>
                <!-- Facilities -->
                <th colspan="4" class="subsection">FACILITIES</th>
                <th>ESTIMATED AMOUNT OF EXPENDITURE</th>
            </tr>
            <tr>
                <th></th>
                <!-- Student Athletes Headers -->
                <th>Scholarships & fees for MALE ATHLETES</th>
                <th>Scholarships & fees for FEMALE ATHLETES</th>
                <th>Monthly Living Allowances for all athletes</th>
                <th>Training Allowances (excluding living allowances)</th>
                <th>Board and Lodging for Student Athletes</th>
                <th>Training Fees for Sports Camps/Clinics</th>
                <th>Medical expenses & insurance for athletes</th>
                <th>Vitamins and Medicines for Student Athletes</th>
                <th>Other expenses for athletes</th>
                <!-- Personnel Headers -->
                <th>Salaries for Athletic Director</th>
                <th>Salaries for Head Coaches</th>
                <th>Salaries for Assistant Coaches</th>
                <th>Salaries for Body Conditioning Trainers</th>
                <th>Salaries for Maintenance Staff</th>
                <th>Salaries of Other Athletic Personnel</th>
                <th>Personnel Uniforms & Supplies</th>
                <th>Training and Seminar Fees for Personnel</th>
                <th>Other expenses for Personnel</th>
                <!-- Competitions Headers -->
                <th>Entry/Registration Fees for Competitions</th>
                <th>Game/Competition Allowances for Athletes</th>
                <th>Game/Competition Incentives for Athletes</th>
                <th>Game/Competition Incentives for Coaches</th>
                <th>Parade Uniforms for Athletes and Personnel</th>
                <th>Game Uniforms for Athletes and Personnel</th>
                <th>Honorarium for Coaches during Competition</th>
                <th>Honorarium for Technical Officials</th>
                <th>Honorarium for Other Staff during Competition</th>
                <th>Sports Equipment & Gadgets for Competitions</th>
                <th>Board and Lodging during Competition</th>
                <th>Transportation during Competition</th>
                <th>First Aid Expenses during Competition</th>
                <th>Athletic Association Membership Fees</th>
                <th>Other expenses related to games/competition</th>
                <!-- Intramurals Headers -->
                <th>Rental of Game Venues for Intrams</th>
                <th>Game Uniforms for Intrams</th>
                <th>Honorarium for Technical Officials for Intrams</th>
                <th>Other expenses related to Intrams</th>
                <!-- Facilities Headers -->
                <th>Renovation/Upgrading of Facilities</th>
                <th>Acquisition of Sports Equipment</th>
                <th>Supplies for maintenance of facilities</th>
                <th>Other expenses related to facilities</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>E-EXPEND-1</td>
                <!-- Student Athletes -->
                <td class="amount">{{ number_format($budget['expenditures']['scholarships_male'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['scholarships_female'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['monthly_allowances'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['training_allowances'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['board_lodging'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['training_fees'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['medical_expenses'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['vitamins_medicines'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['other_athlete_expenses'] ?? 0, 2) }}</td>
                <!-- Personnel -->
                <td class="amount">{{ number_format($budget['expenditures']['salary_athletic_director'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['salary_head_coaches'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['salary_assistant_coaches'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['salary_trainers'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['salary_maintenance_staff'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['salary_other_personnel'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['personnel_uniforms'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['personnel_training'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['other_personnel_expenses'] ?? 0, 2) }}</td>
                <!-- Competitions -->
                <td class="amount">{{ number_format($budget['expenditures']['competition_fees'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['game_allowances_athletes'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['game_incentives_athletes'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['game_incentives_coaches'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['parade_uniforms'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['game_uniforms'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['honorarium_coaches'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['honorarium_officials'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['honorarium_staff'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['sports_equipment'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['board_lodging_competition'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['transportation_competition'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['first_aid_competition'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['association_membership'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['other_competition_expenses'] ?? 0, 2) }}</td>
                <!-- Intramurals -->
                <td class="amount">{{ number_format($budget['expenditures']['venue_rental_intramurals'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['uniforms_intramurals'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['honorarium_officials_intramurals'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['other_intramurals_expenses'] ?? 0, 2) }}</td>
                <!-- Facilities -->
                <td class="amount">{{ number_format($budget['expenditures']['facility_renovation'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['sports_equipment_acquisition'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['maintenance_supplies'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format($budget['expenditures']['other_facility_expenses'] ?? 0, 2) }}</td>
                <td class="amount">{{ number_format(array_sum($budget['expenditures'] ?? []), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- SUMMARY SECTION -->
    <div class="summary">
        <h4>SUMMARY</h4>
        <table style="border: none; background: transparent;">
            <tr>
                <td style="border: none; font-weight: bold;">Estimated Budget:</td>
                <td style="border: none; text-align: right; font-family: monospace;">₱{{ number_format(array_sum($budget['fund_sources'] ?? []), 2) }}</td>
            </tr>
            <tr>
                <td style="border: none; font-weight: bold;">Estimated Expenditures:</td>
                <td style="border: none; text-align: right; font-family: monospace;">₱{{ number_format(array_sum($budget['expenditures'] ?? []), 2) }}</td>
            </tr>
            <tr>
                <td style="border: none; font-weight: bold;">Balance:</td>
                <td style="border: none; text-align: right; font-family: monospace; 
                    color: {{ (array_sum($budget['fund_sources'] ?? []) - array_sum($budget['expenditures'] ?? [])) < 0 ? '#FF0000' : '#008000' }};">
                    ₱{{ number_format(array_sum($budget['fund_sources'] ?? []) - array_sum($budget['expenditures'] ?? []), 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>