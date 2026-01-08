<!DOCTYPE html>
<html>
<head>
    <title>CHED Form G</title>
    <style>
        body { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .header td { border: none; padding: 0; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td style="width:20%">
                <img src="{{ public_path('images/ctu-logo.png') }}" style="width:80px;">
            </td>
            <td style="width:60%; text-align:center;">
                <h3>Cebu Technological University - Main Campus</h3>
                <h4>CHED Form G: Feedback</h4>
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
                <th>G1-FEEDBACK-1</th>
                <th>G1-FEEDBACK-2</th>
                <th>G1-FEEDBACK-3</th>
                <th>G1-FEEDBACK-4</th>
                <th>G1-FEEDBACK-5</th>
                <th>G2-RESPONDENT-1</th>
                <th>G2-RESPONDENT-2</th>
                <th>G2-RESPONDENT-3</th>
            </tr>
            <tr>
                <th>Description</th>
                <th>Template Improvements</th>
                <th>Additional Data Suggestions</th>
                <th>Difficult Data Items</th>
                <th>Easy Data Items</th>
                <th>Additional Comments</th>
                <th>Respondent Name</th>
                <th>Respondent Email</th>
                <th>Submission Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>G-FEED-1</td>
                <td class="text-left">{{ $feedbackData['template_improvements'] ?? 'No response' }}</td>
                <td class="text-left">{{ $feedbackData['additional_data'] ?? 'No response' }}</td>
                <td class="text-left">{{ $feedbackData['difficult_data'] ?? 'No response' }}</td>
                <td class="text-left">{{ $feedbackData['easy_data'] ?? 'No response' }}</td>
                <td class="text-left">{{ $feedbackData['additional_comments'] ?? 'No response' }}</td>
                <td>{{ $feedbackData['respondent_name'] ?? '' }}</td>
                <td>{{ $feedbackData['respondent_email'] ?? '' }}</td>
                <td>
                    @if(isset($feedbackData['submitted_at']) || isset($feedbackData['created_at']))
                        {{ date('Y-m-d', strtotime($feedbackData['submitted_at'] ?? $feedbackData['created_at'])) }}
                    @else
                        {{ date('Y-m-d') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>