<!DOCTYPE html>
<html>
<head>
    <style>
        .header {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            height: 80px;
        }
        .institution-info {
            text-align: center;
            flex-grow: 1;
        }
        .institution-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .institution-address {
            font-size: 12px;
            margin: 2px 0;
            color: #666;
        }
        .form-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            text-decoration: underline;
        }
        .report-info {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <!-- CTU Logo on left -->
            <div>
                @if(file_exists(public_path('images/ctu-logo.png')))
                    <img src="{{ public_path('images/ctu-logo.png') }}" class="logo" alt="CTU Logo">
                @else
                    <div style="width: 80px; height: 80px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                        CTU LOGO
                    </div>
                @endif
            </div>
            
            <!-- Institution Info in center -->
            <div class="institution-info">
                <p class="institution-name">Cebu Technological University</p>
                <p class="institution-address">M.J. Cuenco Ave, Cor R. Palma Street, Cebu City, 6000 Cebu</p>
                <p class="institution-address">Main Campus</p>
            </div>
            
            <!-- CHED Logo on right -->
            <div>
                @if(file_exists(public_path('images/ched-logo.png')))
                    <img src="{{ public_path('images/ched-logo.png') }}" class="logo" alt="CHED Logo">
                @else
                    <div style="width: 80px; height: 80px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                        CHED LOGO
                    </div>
                @endif
            </div>
        </div>
        
        <div class="form-title">
            {{ $formTitle ?? 'CHED REPORT' }}
        </div>
        
        <div class="report-info">
            Generated on: {{ date('F j, Y') }} | HEI: {{ $institutionalData['hei_name'] ?? 'Not specified' }}
        </div>
    </div>
</body>
</html>