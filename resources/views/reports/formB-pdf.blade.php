<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 5px;
        }

        .header-table td {
            vertical-align: middle;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th, td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-size: 9px;
        }

        th {
            background-color: #f59e0b;
            color: #fff;
            font-weight: bold;
        }

        .alt-row {
            background-color: #f9f9f9;
        }

        .logo {
            height: 60px;
        }

        h2, h3 {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <!-- Header with logos -->
    <table class="header-table">
        <tr>
            <td style="width: 15%;"><img src="{{ public_path('images/ctu-logo.png') }}" class="logo"></td>
            <td style="width: 70%; text-align: center;">
                <h2>Cebu Technological University - Main Campus</h2>
                <h3>Form B – Sports Programs Report</h3>
            </td>
            <td style="width: 15%;"><img src="{{ public_path('images/ched-logo.png') }}" class="logo"></td>
        </tr>
    </table>

    <!-- Sports Programs Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ITEM CODE</th>
                <th>B1-SPORTS-1</th>
                <th>B1-SPORTS-2</th>
                <th>B2-ASSOC-1</th>
                <th>B2-ASSOC-2</th>
                <th>B2-ASSOC-3a</th>
                <th>B2-ASSOC-3b</th>
                <th>B2-ASSOC-4</th>
                <th>B3-LEAGUE-1</th>
                <th>B3-LEAGUE-2</th>
                <th>B3-LEAGUE-3</th>
                <th>B3-LEAGUE-4</th>
                <th>B3-LEAGUE-5</th>
                <th>B3-LEAGUE-6</th>
                <th>B3-LEAGUE-7</th>
                <th>B3-LEAGUE-8</th>
                <th>B3-LEAGUE-9</th>
                <th>B3-LEAGUE-10</th>
                <th>B4-WELL-1</th>
                <th>B4-WELL-2</th>
                <th>B4-WELL-3</th>
                <th>B5-CPD-1</th>
                <th>B5-CPD-2</th>
                <th>B5-CPD-3</th>
                <th>B5-CPD-4</th>
                <th>B5-CPD-5</th>
                <th>B5-CPD-6</th>
                <th>B5-CPD-7</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sports as $index => $sport)
                @php $sp = $sportsPrograms[$sport->sport_id] ?? []; @endphp
                <tr class="{{ $index % 2 == 0 ? 'alt-row' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sport->sport_code ?? 'B1-SPORT-' . ($index + 1) }}</td>
                    <td>{{ $sport->sport_name ?? '' }}</td>
                    <td>{{ $sp['category'] ?? '' }}</td>
                    <td>{{ $sp['assoc_1'] ?? '' }}</td>
                    <td>{{ $sp['assoc_2'] ?? '' }}</td>
                    <td>{{ $sp['assoc_3a'] ?? '' }}</td>
                    <td>{{ $sp['assoc_3b'] ?? '' }}</td>
                    <td>{{ $sp['assoc_other'] ?? '' }}</td>

                    @for($i = 1; $i <= 10; $i++)
                        @php
                            $active = 'league_active_' . ceil($i/2);
                            $count = 'league_count_' . ceil($i/2);
                            $value = $i % 2 == 1 ? ($sp[$active] ?? '') : ($sp[$count] ?? '');
                        @endphp
                        <td>{{ $value }}</td>
                    @endfor

                    @for($i = 1; $i <= 3; $i++)
                        <td>{{ $sp['well_' . $i] ?? '' }}</td>
                    @endfor

                    @for($i = 1; $i <= 7; $i++)
                        <td>{{ $sp['cpd_' . $i] ?? '' }}</td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
