<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM F: Non-Varsity Clubs</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; line-height: 1.2; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .generated-on { text-align: right; font-style: italic; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: left; }
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
                <th>#</th><th>Sports</th><th>Sports (Sex)</th><th>Sports Club Name</th>
                <th>Club Moderator</th><th>Main Program/Activity</th><th>Secondary Program/Activity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nonVarsityClubs as $index => $club)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $club->sport_name }}</td>
                <td>{{ $club->sports_sex }}</td>
                <td>{{ $club->sports_club_name }}</td>
                <td>{{ $club->club_moderator }}</td>
                <td>{{ $club->main_program_activity }}</td>
                <td>{{ $club->secondary_program_activity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>