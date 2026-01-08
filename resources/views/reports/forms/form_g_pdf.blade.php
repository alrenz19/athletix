<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CHED FORM G: Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .logo-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .generated-on { text-align: right; font-style: italic; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; background-color: #e6e6fa; padding: 8px; margin: 15px 0; }
        .question { font-weight: bold; margin-bottom: 5px; }
        .response { margin-bottom: 15px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #333; }
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

    <div class="section">
        <div class="question">How can these templates be improved?</div>
        <div class="response">{{ $feedbackData->template_improvements ?? 'No response provided' }}</div>

        <div class="question">What other important sports data should be captured/measured?</div>
        <div class="response">{{ $feedbackData->additional_data ?? 'No response provided' }}</div>

        <div class="question">What items were the most difficult to find data on?</div>
        <div class="response">{{ $feedbackData->difficult_data ?? 'No response provided' }}</div>

        <div class="question">What items were the most easiest to find data on?</div>
        <div class="response">{{ $feedbackData->easy_data ?? 'No response provided' }}</div>

        <div class="question">Additional Comments or Suggestions</div>
        <div class="response">{{ $feedbackData->additional_comments ?? 'No response provided' }}</div>
    </div>

    <div class="section">
        <div class="section-title">RESPONDENT INFORMATION</div>
        <div><strong>Name:</strong> {{ $feedbackData->respondent_name ?? 'Not provided' }}</div>
        <div><strong>Email:</strong> {{ $feedbackData->respondent_email ?? 'Not provided' }}</div>
    </div>
</body>
</html>