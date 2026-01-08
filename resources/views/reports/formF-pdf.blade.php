<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form F – Non-Varsity School-Based Sports Organizations</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
        .subtitle { text-align: center; font-style: italic; font-size: 12px; margin-bottom: 15px; }
        .logos { width: 100%; display: flex; justify-content: space-between; margin-bottom: 10px; }
        .logos img { height: 60px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 4px; text-align: left; }
        th.section { background-color: #e0e7ff; text-align: center; font-weight: bold; }
        th.subheader { background-color: #f1f5f9; font-weight: bold; }
        .total { background-color: #f1f5f9; font-weight: bold; text-align: center; padding: 8px; }
        .item-code { text-align: center; }
    </style>
</head>
<body>
    <div class="logos">
        <img src="{{ public_path('images/ctu-logo.png') }}" alt="CTU Logo">
        <img src="{{ public_path('images/ched-logo.png') }}" alt="CHED Logo">
    </div>

    <div class="header">Cebu Technological University - Main Campus</div>
    <div class="subtitle">Form F – Non-Varsity School-Based Sports Organizations</div>

    <table>
        <thead>
            <tr>
                <th colspan="7" class="section">FORM F: NON-VARSITY SCHOOL-BASED SPORTS ORGANIZATIONS</th>
            </tr>
            <tr>
                <th class="subheader">ITEM CODE</th>
                <th class="subheader">F1-CLUB-1</th>
                <th class="subheader">F1-CLUB-2</th>
                <th class="subheader">F1-CLUB-3</th>
                <th class="subheader">F2-MOD-1</th>
                <th class="subheader">F3-ACTIV-1</th>
                <th class="subheader">F3-ACTIV-2</th>
            </tr>
            <tr>
                <th class="subheader">Description</th>
                <th class="subheader">Sports</th>
                <th class="subheader">Sports (Sex)</th>
                <th class="subheader">Sports Club Name</th>
                <th class="subheader">Name of Designated Club Moderator</th>
                <th class="subheader">Main Program/Activity</th>
                <th class="subheader">Secondary Program/Activity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nonVarsityClubs as $index => $club)
            <tr>
                <td class="item-code">{{ $index + 1 }}</td>
                <td>{{ $club->sport_name ?? '' }}</td>
                <td>{{ $club->sports_sex ?? '' }}</td>
                <td>{{ $club->sports_club_name ?? '' }}</td>
                <td>{{ $club->club_moderator ?? '' }}</td>
                <td>{{ $club->main_program_activity ?? '' }}</td>
                <td>{{ $club->secondary_program_activity ?? '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 10px;">No non-varsity sports clubs found.</td>
            </tr>
            @endforelse
        </tbody>
        @if($nonVarsityClubs->count() > 0)
        <tfoot>
            <tr>
                <td colspan="7" class="total">Total Clubs: {{ $nonVarsityClubs->count() }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>