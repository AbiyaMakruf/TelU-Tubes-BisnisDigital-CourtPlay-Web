<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analysis Complete - CourtPlay</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap');

        body {
            background-color: #1c1c1c; /* black-200 */
            color: #fafafa; /* white-500 */
            font-family: 'Lexend', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            background-color: #292929; /* black-300 */
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            margin: 40px auto;
        }

        .logo {
            display: block;
            margin: 0 auto 24px;
            width: 140px;
        }

        h2 {
            color: #f4fdca; /* primary-500 */
            font-weight: 700;
            margin-bottom: 8px;
        }

        p {
            color: #d4d4d4; /* white-200 */
            margin-bottom: 15px;
        }

        .highlight {
            color: #a3ce14; /* primary-300 */
            font-weight: 600;
        }

        .button {
            display: inline-block;
            background-color: #a3ce14; /* primary-300 */
            color: #1c1c1c; /* black-200 */
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
        }

        .analysis-summary {
            background-color: #1c1c1c; /* black-200 */
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 0.95em;
        }
        .analysis-summary p {
            margin: 5px 0;
            color: #fafafa;
        }
        .analysis-summary span {
            font-weight: 600;
            color: #f4fdca; /* primary-500 */
        }

        .footer {
            color: #888;
            font-size: 0.85em;
            margin-top: 40px;
            border-top: 1px solid #444;
            padding-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://storage.googleapis.com/courtplay-storage/assets/Web/Logo-Horizontal.png" alt="CourtPlay Logo" class="logo">

        <h2>Hello, {{ $user->first_name }}! 🎉</h2>

        <p>Your video analysis project is complete! The CourtPlay AI has processed **{{ $project->project_name }}** and your full report is now ready.</p>

        <div class="analysis-summary">
            <p><span class="highlight">Project Name:</span> {{ $project->project_name }}</p>
            <p><span class="highlight">Upload Date:</span> {{ $project->upload_date->format('d M Y') }}</p>
            @if ($details)
                <p style="margin-top: 10px;">Initial Stroke Summary:</p>
                <p><span>Forehand Count:</span> {{ $details->forehand_count }}</p>
                <p><span>Backhand Count:</span> {{ $details->backhand_count }}</p>
                <p><span>Serve Count:</span> {{ $details->serve_count }}</p>
                <p style="margin-top: 10px;"><span>Video Duration:</span> {{ $details->video_duration }} seconds</p>
            @else
                <p>Detailed analytics will be loaded on the report page.</p>
            @endif
        </div>

        <p>Click the button below to view your game metrics, shot heatmaps, and all performance insights. It's time to see your progress!</p>

        <a href="{{ $reportUrl }}" class="button">
            View Analysis Report 📊
        </a>

        <p style="margin-top: 30px;">Happy analyzing and take your game to the next level! 🚀</p>

        <div class="footer">
            <p>This email was sent automatically. If you have any questions, please reply to this email or visit our Help Center.<br>
            © {{ date('Y') }} CourtPlay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
