<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plan Updated - CourtPlay</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap');

        body {
            background-color: #1c1c1c;
            color: #fafafa;
            font-family: 'Lexend', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            background-color: #292929;
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
            color: #f4fdca;
            font-weight: 700;
            margin-bottom: 8px;
        }

        p {
            color: #d4d4d4;
            margin-bottom: 15px;
        }

        .highlight {
            color: #a3ce14;
            font-weight: 600;
        }

        .button {
            display: inline-block;
            background-color: #a3ce14;
            color: #1c1c1c;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
        }

        .plan-summary {
            background-color: #1c1c1c;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 0.95em;
        }

        .plan-summary p {
            margin: 6px 0;
            color: #fafafa;
        }

        .plan-summary span {
            font-weight: 600;
            color: #f4fdca;
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

        <h2>Hello, {{ $user->first_name }}! 👋</h2>

        <p>We’re excited to let you know that your <span class="highlight">CourtPlay Plan</span> has been updated successfully.</p>

        <div class="plan-summary">
            <p><span>Previous Plan:</span> {{ ucfirst($oldPlan ?? 'Free') }}</p>
            <p><span>New Plan:</span> {{ ucfirst($newPlan) }}</p>
            <p><span>Updated On:</span> {{ now()->format('d M Y, H:i') }}</p>

            @if ($newPlan === 'plus')
                <p style="margin-top:10px;">🎾 Enjoy your <span class="highlight">Plus</span> features:</p>
                <ul style="color:#fafafa; margin-left:18px; margin-top:6px;">
                    <li>Unlimited match uploads</li>
                    <li>Full heatmap & shot analytics</li>
                    <li>Personal performance reports</li>
                </ul>
            @elseif ($newPlan === 'pro')
                <p style="margin-top:10px;">🏆 Welcome to <span class="highlight">CourtPlay Pro</span>!</p>
                <ul style="color:#fafafa; margin-left:18px; margin-top:6px;">
                    <li>Priority analysis queue</li>
                    <li>AI-powered tactical insights</li>
                    <li>Advanced shot visualization</li>
                </ul>
            @else
                <p style="margin-top:10px;">Your plan has been updated to <span class="highlight">{{ ucfirst($newPlan) }}</span>.</p>
            @endif
        </div>

        <p>Click below to view your dashboard and explore your new features:</p>

        <a href="{{ $dashboardUrl }}" class="button">
            Go to My Dashboard →
        </a>

        <p style="margin-top:30px;">Thank you for trusting CourtPlay to elevate your game. 🚀</p>

        <div class="footer">
            <p>This email was sent automatically by CourtPlay.<br>
            If you have any questions, please reply to this email or visit our Help Center.<br>
            © {{ date('Y') }} CourtPlay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
