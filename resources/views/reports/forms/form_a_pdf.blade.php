<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM A: Institutional Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
            margin-bottom: 8px;
            border-left: 4px solid #333;
            font-size: 13px;
        }
        .data-row {
            display: flex;
            margin-bottom: 4px;
            page-break-inside: avoid;
        }
        .field-name {
            font-weight: bold;
            width: 280px;
            padding-right: 15px;
            border-right: 1px solid #ddd;
        }
        .field-value {
            flex: 1;
            padding-left: 15px;
        }
        .generated-on {
            text-align: right;
            font-style: italic;
            margin-bottom: 20px;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
        .facility-row {
            display: flex;
            margin-bottom: 3px;
        }
        .facility-name {
            width: 200px;
            font-weight: bold;
        }
        .facility-value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        @media print {
            body {
                margin: 0;
                font-size: 11px;
            }
            .header {
                margin-bottom: 15px;
            }
            .section {
                margin-bottom: 15px;
            }
        }
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

    <div class="generated-on">
        <strong>Generated on:</strong> {{ $generatedOn }}
    </div>

    <!-- HEI Information Section -->
    <div class="section">
        <div class="section-title">HEI INFORMATION</div>
        
        <div class="data-row">
            <div class="field-name">Name of HEI:</div>
            <div class="field-value">{{ $institutionalData->hei_name ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">HEI Campus (if applicable):</div>
            <div class="field-value">{{ $institutionalData->hei_campus ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Address of HEI:</div>
            <div class="field-value">{{ $institutionalData->hei_address ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Name of HEI President:</div>
            <div class="field-value">{{ $institutionalData->hei_president ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Sports Director Information Section -->
    <div class="section">
        <div class="section-title">SPORTS DIRECTOR INFORMATION</div>
        
        <div class="data-row">
            <div class="field-name">Name of Sports Director:</div>
            <div class="field-value">{{ $institutionalData->sports_director_name ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">E-mail of Sports Director:</div>
            <div class="field-value">{{ $institutionalData->sports_director_email ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Alternate E-mail of Sports Director:</div>
            <div class="field-value">{{ $institutionalData->sports_director_alt_email ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Mobile No. of Sports Director:</div>
            <div class="field-value">{{ $institutionalData->sports_director_mobile ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Contact Person Information Section -->
    <div class="section">
        <div class="section-title">CONTACT PERSON INFORMATION</div>
        
        <div class="data-row">
            <div class="field-name">Name of Contact Person for RA 11180 Sports Reporting:</div>
            <div class="field-value">{{ $institutionalData->contact_person_name ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">E-mail of Contact Person for RA 11180 Sports Reporting:</div>
            <div class="field-value">{{ $institutionalData->contact_person_email ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Alternate E-mail of Contact Person for RA 11180 Sports Reporting:</div>
            <div class="field-value">{{ $institutionalData->contact_person_alt_email ?? 'Not provided' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Contact No. of Contact Person for RA 11180 Sports Reporting:</div>
            <div class="field-value">{{ $institutionalData->contact_person_mobile ?? 'Not provided' }}</div>
        </div>
    </div>

    <!-- Intramurals Information Section -->
    <div class="section">
        <div class="section-title">INTRAMURALS INFORMATION</div>
        
        <div class="data-row">
            <div class="field-name">Does HEI hold Departmental Intramurals within the campus?</div>
            <div class="field-value">{{ $institutionalData->departmental_intramurals ?? 'Not specified' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Does HEI hold Interdepartmental Intramurals within the campus?</div>
            <div class="field-value">{{ $institutionalData->interdepartmental_intramurals ?? 'Not specified' }}</div>
        </div>
        
        <div class="data-row">
            <div class="field-name">Does HEI hold Inter-campus Intramurals?</div>
            <div class="field-value">{{ $institutionalData->intercampus_intramurals ?? 'Not specified' }}</div>
        </div>
    </div>

    <!-- Facilities Information Section -->
    <div class="section">
        <div class="section-title">FACILITIES INFORMATION</div>
        
        @php
            $facilities = $institutionalData->facilities ?? [];
        @endphp
        
        <table>
            <thead>
                <tr>
                    <th style="width: 60%;">Facility</th>
                    <th style="width: 40%;">Available</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>With Gymnasium? w/in HEI</td>
                    <td>{{ $facilities['gymnasium'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Multi-purpose Hall For Indoor Sports?</td>
                    <td>{{ $facilities['multipurpose_hall'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Quadrangle?</td>
                    <td>{{ $facilities['quadrangle'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Athletes Dormitory?</td>
                    <td>{{ $facilities['athletes_dormitory'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Swimming Pool?</td>
                    <td>{{ $facilities['swimming_pool'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Track Oval?</td>
                    <td>{{ $facilities['track_oval'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Dance Studio?</td>
                    <td>{{ $facilities['dance_studio'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Tennis Court?</td>
                    <td>{{ $facilities['tennis_court'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Football field?</td>
                    <td>{{ $facilities['football_field'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>With Boxing Ring?</td>
                    <td>{{ $facilities['boxing_ring'] ?? 'Not specified' }}</td>
                </tr>
                <tr>
                    <td>Dedicated Sports Medical Facility</td>
                    <td>{{ $facilities['sports_medical_facility'] ?? 'Not specified' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Other Facilities -->
        <div class="data-row">
            <div class="field-name">Other Training Venues and Athletic Facilities:</div>
            <div class="field-value">{{ $institutionalData->other_facilities ?? 'None specified' }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666;">
        <p>This document was automatically generated by the CHED Sports Reporting System</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>