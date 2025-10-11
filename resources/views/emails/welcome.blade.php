<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CourtPlay</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap');

        body {
            background-color: #1c1c1c; /* black-200 */
            color: #fafafa; /* white-500 */
            font-family: 'Lexend', sans-serif;
            margin: 0;
            padding: 0;
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
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .highlight {
            color: #a3ce14; /* primary-300 */
            font-weight: 600;
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

        <h2>Hello, {{ $user->firstname }} 👋</h2>

        <p>Welcome to <span class="highlight">CourtPlay</span> — where <strong>AI meets tennis and padel analytics</strong>.</p>
        <p>You're now part of our community that empowers players and coaches with intelligent video analysis and performance insights.</p>

        <p style="margin-top: 24px;">✨ Here’s what you can unlock when you upgrade:</p>
        <ul style="color:#f4fdca; line-height:1.7;">
            <li><strong>Plus Plan:</strong> Unlock <span class="highlight">5 video analytics</span> per month with advanced performance charts.</li>
            <li><strong>Pro Plan:</strong> Get <span class="highlight">unlimited uploads</span>, AI mapping tools, and detailed match reports.</li>
        </ul>

        <p>Start exploring your court, and let AI take your game to the next level. 🎾</p>

        <div class="footer">
            <p>If you didn’t sign up for CourtPlay, please ignore this email.<br>
            © {{ date('Y') }} CourtPlay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
