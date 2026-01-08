<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM D: Sports Personnel</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; line-height: 1.1; }
        .header { text-align: center; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .generated-on { text-align: right; font-style: italic; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th, td { border: 1px solid #ddd; padding: 3px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <div style="text-align: left; width: 30%;">
                <img src="{{ public_path('images/ctu-logo.png') }}" style="height: 60px;" alt="CTU Logo">
            </div>
            <div style="text-align: center; width: 40%;">
                <h2 style="margin: 0; color: #333;">CHED FORM A: INSTITUTIONAL INFORMATION</h2>
                <p style="margin: 5px 0 0 0; font-size: 11px; color: #666;">Commission on Higher Education</p>
            </div>
            <div style="text-align: right; width: 30%;">
                <img src="{{ public_path('images/ched-logo.png') }}" style="height: 60px;" alt="CHED Logo">
            </div>
        </div>
    </div>

    <div class="generated-on">Generated on: {{ $generatedOn }} | HEI: {{ $institutionalData->hei_name ?? '' }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Age</th><th>Gender</th><th>Sport</th><th>Position</th>
                <th>Role</th><th>Employment</th><th>Salary</th><th>Years Exp</th>
                <th>Previous Athlete</th><th>Athlete Level</th><th>Athlete Accomplishment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coaches as $index => $coach)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $coach->full_name }}</td>
                <td>{{ $coach->age }}</td>
                <td>{{ $coach->gender }}</td>
                <td>{{ $coach->sport->sport_name ?? '' }}</td>
                <td>{{ $coach->position_title }}</td>
                <td>{{ $coach->assigned_role }}</td>
                <td>{{ $coach->employment_status }}</td>
                <td>₱{{ number_format($coach->monthly_salary, 2) }}</td>
                <td>{{ $coach->years_experience }}</td>
                <td>{{ $coach->was_previous_athlete ? 'Yes' : 'No' }}</td>
                <td>{{ $coach->highest_competition_level }}</td>
                <td>{{ $coach->highest_accomplishment_athlete }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>