<!DOCTYPE html>
<html>
<head>
    <title>CHED Form A</title>
    <style>
        body { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .header td { border: none; padding: 0; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td style="width:20%">
                <img src="{{ public_path('images/ctu-logo.png') }}" style="width:80px;">
            </td>
            <td style="width:60%; text-align:center;">
                <h3>Cebu Technological University</h3>
                <p>Cebu Main Campus</p>
                <h4>CHED Form A: Institutional Information</h4>
            </td>
            <td style="width:20%; text-align:right;">
                <img src="{{ public_path('images/ched-logo.png') }}" style="width:80px;">
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>ITEM CODE</th>
                <th>A1-HEI-1</th>
                <th>A1-HEI-2</th>
                <th>A1-HEI-3</th>
                <th>A1-HEI-4</th>
                <th>A1-HEI-5</th>
                <th>A1-HEI-6</th>
                <th>A1-HEI-7</th>
                <th>A1-HEI-8</th>
                <th>A1-HEI-9</th>
                <th>A1-HEI-10</th>
                <th>A1-HEI-11</th>
                <th>A1-HEI-12</th>
                <th>A2-WELL-1</th>
                <th>A2-WELL-2</th>
                <th>A2-WELL-3</th>
                <th>A3-FACILITY-1</th>
                <th>A3-FACILITY-2</th>
                <th>A3-FACILITY-3</th>
                <th>A3-FACILITY-4</th>
                <th>A3-FACILITY-5</th>
                <th>A3-FACILITY-6</th>
                <th>A3-FACILITY-7</th>
                <th>A3-FACILITY-8</th>
                <th>A3-FACILITY-9</th>
                <th>A3-FACILITY-10</th>
                <th>A3-FACILITY-11</th>
                <th>A3-FACILITY-12</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>A-INST-1</td>
                <td>{{ $data['hei_name'] ?? '' }}</td>
                <td>{{ $data['hei_campus'] ?? '' }}</td>
                <td>{{ $data['hei_address'] ?? '' }}</td>
                <td>{{ $data['hei_president'] ?? '' }}</td>
                <td>{{ $data['sports_director_name'] ?? '' }}</td>
                <td>{{ $data['sports_director_email'] ?? '' }}</td>
                <td>{{ $data['sports_director_alt_email'] ?? '' }}</td>
                <td>{{ $data['sports_director_mobile'] ?? '' }}</td>
                <td>{{ $data['contact_person_name'] ?? '' }}</td>
                <td>{{ $data['contact_person_email'] ?? '' }}</td>
                <td>{{ $data['contact_person_alt_email'] ?? '' }}</td>
                <td>{{ $data['contact_person_mobile'] ?? '' }}</td>
                <td>{{ $data['departmental_intramurals'] ?? '' }}</td>
                <td>{{ $data['interdepartmental_intramurals'] ?? '' }}</td>
                <td>{{ $data['intercampus_intramurals'] ?? '' }}</td>
                @php $f = $facilities ?? []; @endphp
                <td>{{ $f['gymnasium'] ?? '' }}</td>
                <td>{{ $f['multipurpose_hall'] ?? '' }}</td>
                <td>{{ $f['quadrangle'] ?? '' }}</td>
                <td>{{ $f['athletes_dormitory'] ?? '' }}</td>
                <td>{{ $f['swimming_pool'] ?? '' }}</td>
                <td>{{ $f['track_oval'] ?? '' }}</td>
                <td>{{ $f['dance_studio'] ?? '' }}</td>
                <td>{{ $f['tennis_court'] ?? '' }}</td>
                <td>{{ $f['football_field'] ?? '' }}</td>
                <td>{{ $f['boxing_ring'] ?? '' }}</td>
                <td>{{ $f['sports_medical_facility'] ?? '' }}</td>
                <td>{{ $data['other_facilities'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
