<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM C: Student Athletes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 8px; line-height: 1.1; }
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
                <th>#</th><th>Name</th><th>Age</th><th>Sport</th><th>Gender</th><th>Course</th>
                <th>Competition Level</th><th>Accomplishment</th><th>International Comp</th>
                <th>Regional Training</th><th>National Training</th><th>International Training</th>
                <th>Training Days</th><th>Hours/Day</th><th>Scholarship</th>
            </tr>
        </thead>
        <tbody>
            @foreach($athletes as $index => $athlete)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $athlete->full_name }}</td>
                <td>{{ $athlete->age }}</td>
                <td>{{ $athlete->sport->sport_name ?? '' }}</td>
                <td>{{ ucfirst($athlete->gender) }}</td>
                <td>{{ $athlete->academic_course }}</td>
                <td>{{ $athlete->highest_competition_level }}</td>
                <td>{{ $athlete->highest_accomplishment }}</td>
                <td>{{ $athlete->international_competition_name }}</td>
                <td>{{ $athlete->training_seminars_regional ? 'Yes' : 'No' }}</td>
                <td>{{ $athlete->training_seminars_national ? 'Yes' : 'No' }}</td>
                <td>{{ $athlete->training_seminars_international ? 'Yes' : 'No' }}</td>
                <td>{{ $athlete->training_frequency_days }}</td>
                <td>{{ $athlete->training_hours_per_day }}</td>
                <td>{{ $athlete->scholarship_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>