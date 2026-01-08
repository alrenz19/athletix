<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM E: Budget and Expenditure</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; line-height: 1.3; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .generated-on { text-align: right; font-style: italic; margin-bottom: 15px; }
        .section { margin-bottom: 15px; }
        .section-title { font-weight: bold; background-color: #e6e6fa; padding: 6px; margin: 10px 0; }
        .data-row { display: flex; margin-bottom: 2px; }
        .field-name { font-weight: bold; width: 300px; padding-right: 10px; }
        .field-value { flex: 1; text-align: right; }
        .total { font-weight: bold; background-color: #ffffcc; padding: 4px; }
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

    <div class="generated-on">Generated on: {{ $generatedOn }} | HEI: {{ $institutionalData->hei_name ?? '' }} | AY: 2022-2023</div>

    <div class="section">
        <div class="section-title">SECTION E1: FUND SOURCES</div>
        @php
            $fundSources = $budgetData->fund_sources ?? [];
            $totalFunds = array_sum($fundSources ?? []);
        @endphp
        <div class="data-row"><div class="field-name">Athletic Fee Per Student Per Semester:</div><div class="field-value">₱{{ number_format($fundSources['athletic_fee_per_student'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Collection from Athletic Fees/Miscellaneous:</div><div class="field-value">₱{{ number_format($fundSources['collection_athletic_fees'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Collection from Donors:</div><div class="field-value">₱{{ number_format($fundSources['collection_donors'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Fundraising and/or Income Generating Programs:</div><div class="field-value">₱{{ number_format($fundSources['fundraising_income'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Funding Support from Local Government:</div><div class="field-value">₱{{ number_format($fundSources['local_govt_funding'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Funding Support from National Government:</div><div class="field-value">₱{{ number_format($fundSources['national_govt_funding'] ?? 0, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Other Sources Not Included Above:</div><div class="field-value">₱{{ number_format($fundSources['other_sources'] ?? 0, 2) }}</div></div>
        <div class="data-row total"><div class="field-name">TOTAL FUND SOURCES:</div><div class="field-value">₱{{ number_format($totalFunds, 2) }}</div></div>
    </div>

    <div class="section">
        <div class="section-title">SECTION E2: EXPENDITURES</div>
        @php
            $expenditures = $budgetData->expenditures ?? [];
            $totalExpenditures = array_sum($expenditures ?? []);
        @endphp
        <div class="data-row"><div class="field-name">Total Expenditures:</div><div class="field-value">₱{{ number_format($totalExpenditures, 2) }}</div></div>
    </div>

    <div class="section">
        <div class="section-title">SUMMARY</div>
        <div class="data-row"><div class="field-name">Total Fund Sources:</div><div class="field-value">₱{{ number_format($totalFunds, 2) }}</div></div>
        <div class="data-row"><div class="field-name">Total Expenditures:</div><div class="field-value">₱{{ number_format($totalExpenditures, 2) }}</div></div>
        @php $balance = $totalFunds - $totalExpenditures; @endphp
        <div class="data-row total"><div class="field-name">Balance:</div><div class="field-value">₱{{ number_format($balance, 2) }}</div></div>
    </div>
</body>
</html>