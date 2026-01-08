<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED Form D – Sports Personnel</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; }
        th { background-color: #4B0082; color: #fff; font-size: 10px; }
        .odd { background-color: #f2f2f2; }
        .even { background-color: #ffffff; }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { width: 60px; height: 60px; }
        .main-title { font-weight: bold; font-size: 14px; }
        .subtitle { font-style: italic; font-size: 12px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td><img src="{{ public_path('images/ctu-logo.png') }}" class="logo"></td>
            <td class="header">
                <div class="main-title">Cebu Technological University - Main Campus</div>
                <div class="subtitle">Form D – Sports Personnel Report</div>
            </td>
            <td><img src="{{ public_path('images/ched-logo.png') }}" class="logo"></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ITEM CODE</th>
                <th>D1-PROFILE-1</th>
                <th>D1-PROFILE-2</th>
                <th>D1-PROFILE-3</th>
                <th>D1-PROFILE-4</th>
                <th>D1-PROFILE-5</th>
                <th>D1-PROFILE-6</th>
                <th>D1-PROFILE-7</th>
                <th>D1-PROFILE-8</th>
                <th>D1-PROFILE-9</th>
                <th>D2-EXP-1</th>
                <th>D2-EXP-2</th>
                <th>D2-EXP-3</th>
                <th>D2-EXP-4</th>
                <th>D2-EXP-5</th>
                <th>D2-EXP-6</th>
                <th>D3-LIC-1</th>
                <th>D3-LIC-2</th>
                <th>D3-LIC-3</th>
                <th>D3-LIC-4</th>
                <th>D4-EDUC-1</th>
                <th>D4-EDUC-2</th>
                <th>D4-EDUC-3</th>
                <th>D4-EDUC-4</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coaches as $index => $coach)
            <tr class="{{ $index % 2 == 0 ? 'even' : 'odd' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ 'D-PERS-' . ($index + 1) }}</td>
                <td>{{ $coach['full_name'] ?? '-' }}</td>
                <td>{{ $coach['age'] ?? '-' }}</td>
                <td>{{ $coach['gender'] ?? '-' }}</td>
                <td>{{ $coach['sport']['sport_name'] ?? '-' }}</td>
                <td>{{ $coach['current_position_title'] ?? '-' }}</td>
                <td>{{ $coach['sports_program_position'] ?? '-' }}</td>
                <td>{{ $coach['employment_status'] ?? '-' }}</td>
                <td>{{ $coach['monthly_salary'] ?? '-' }}</td>
                <td>{{ $coach['years_experience'] ?? '-' }}</td>
                <td>{{ $coach['was_previous_athlete'] ? 'Yes' : 'No' }}</td>
                <td>{{ $coach['highest_competition_level'] ?? '-' }}</td>
                <td>{{ $coach['highest_accomplishment_athlete'] ?? '-' }}</td>
                <td>{{ $coach['international_competition_athlete'] ?? '-' }}</td>
                <td>{{ $coach['highest_accomplishment_coach'] ?? '-' }}</td>
                <td>{{ $coach['international_competition_coach'] ?? '-' }}</td>
                <td>{{ $coach['regional_membership'] ? 'Yes' : 'No' }}</td>
                <td>{{ $coach['national_membership'] ? 'Yes' : 'No' }}</td>
                <td>{{ $coach['international_membership'] ? 'Yes' : 'No' }}</td>
                <td>{{ $coach['international_membership_name'] ?? '-' }}</td>
                <td>{{ $coach['highest_degree'] ?? '-' }}</td>
                <td>{{ $coach['bachelor_degree'] ?? '-' }}</td>
                <td>{{ $coach['master_degree'] ?? '-' }}</td>
                <td>{{ $coach['doctorate_degree'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
