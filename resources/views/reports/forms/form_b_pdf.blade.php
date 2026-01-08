<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM B: Sports Programs</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; line-height: 1.2; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .generated-on { text-align: right; font-style: italic; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; text-align: center; }
        .section-title { font-weight: bold; background-color: #e6e6fa; padding: 6px; margin: 10px 0; }
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
                <th>#</th><th>Sport Name</th><th>Category</th><th>SCUAA</th><th>ALCUA</th><th>PRISAA</th>
                <th>National Games</th><th>Other Associations</th><th>Assoc Leagues Active</th><th>No. Assoc Leagues</th>
                <th>Provincial Active</th><th>No. Provincial</th><th>Regional Active</th><th>No. Regional</th>
                <th>National Active</th><th>No. National</th><th>International Active</th><th>No. International</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sportsPrograms as $index => $program)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $program->sport->sport_name ?? '' }}</td>
                <td>{{ $program->category ?? '' }}</td>
                <td>{{ $program->assoc_1 ?? '' }}</td>
                <td>{{ $program->assoc_2 ?? '' }}</td>
                <td>{{ $program->assoc_3a ?? '' }}</td>
                <td>{{ $program->assoc_3b ?? '' }}</td>
                <td>{{ $program->assoc_other ?? '' }}</td>
                <td>{{ $program->league_active_1 ?? '' }}</td>
                <td>{{ $program->league_count_1 ?? '' }}</td>
                <td>{{ $program->league_active_2 ?? '' }}</td>
                <td>{{ $program->league_count_2 ?? '' }}</td>
                <td>{{ $program->league_active_3 ?? '' }}</td>
                <td>{{ $program->league_count_3 ?? '' }}</td>
                <td>{{ $program->league_active_4 ?? '' }}</td>
                <td>{{ $program->league_count_4 ?? '' }}</td>
                <td>{{ $program->league_active_5 ?? '' }}</td>
                <td>{{ $program->league_count_5 ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>