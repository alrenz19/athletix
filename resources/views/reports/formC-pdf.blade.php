<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header-table { width: 100%; margin-bottom: 5px; }
        .header-table td { vertical-align: middle; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center; font-size: 9px; }
        th { background-color: #1e40af; color: #fff; font-weight: bold; }
        .alt-row { background-color: #f9f9f9; }
        .logo { height: 60px; }
        h2, h3 { margin: 2px 0; }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 15%;"><img src="{{ public_path('images/ctu-logo.png') }}" class="logo"></td>
            <td style="width: 70%; text-align: center;">
                <h2>Cebu Technological University - Main Campus</h2>
                <h3>Form C – Profile and Benefits of Student-Athletes</h3>
            </td>
            <td style="width: 15%;"><img src="{{ public_path('images/ched-logo.png') }}" class="logo"></td>
        </tr>
    </table>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name of Student</th>
                <th>Age</th>
                <th>Sports Program</th>
                <th>Sex</th>
                <th>Academic Course</th>
                <th>Highest Level of Competition</th>
                <th>Highest Accomplishment</th>
                <th>International Competition</th>
                <th>Regional Training</th>
                <th>National Training</th>
                <th>International Training</th>
                <th>Training Frequency (Days/Week)</th>
                <th>Training Hours/Day</th>
                <th>Scholarship Status</th>
                <th>Monthly Allowance</th>
                <th>Board & Lodging</th>
                <th>Medical Insurance</th>
                <th>Training Uniforms</th>
                <th>Average Tournament Allowance</th>
                <th>Playing Uniforms Sponsorship</th>
                <th>Playing Gears Sponsorship</th>
                <th>Excused from Academic Obligations</th>
                <th>Flexible Academic Schedule</th>
                <th>Academic Tutorials Support</th>
            </tr>
        </thead>
        <tbody>
            @foreach($athletes as $index => $athlete)
            <tr class="{{ $index % 2 == 0 ? 'alt-row' : '' }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $athlete->full_name }}</td>
                <td>{{ $athlete->age }}</td>
                <td>{{ optional($athlete->sport)->sport_name }}</td>
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
                <td>{{ $athlete->monthly_living_allowance }}</td>
                <td>{{ $athlete->board_lodging_support }}</td>
                <td>{{ $athlete->medical_insurance_support }}</td>
                <td>{{ $athlete->training_uniforms_support }}</td>
                <td>{{ $athlete->average_tournament_allowance }}</td>
                <td>{{ $athlete->playing_uniforms_sponsorship }}</td>
                <td>{{ $athlete->playing_gears_sponsorship }}</td>
                <td>{{ $athlete->excused_from_academic_obligations }}</td>
                <td>{{ $athlete->flexible_academic_schedule }}</td>
                <td>{{ $athlete->academic_tutorials_support }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
