<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password | CourtPlay</title>
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
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #d4d4d4; /* white-200 */
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .btn {
            display: inline-block;
            background-color: #a3ce14; /* primary-300 */
            color: #0c0c0c; /* black-100 */
            text-decoration: none;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px 24px;
            margin-top: 16px;
        }

        .btn:hover {
            background-color: #d7f462; /* primary-400 */
        }

        .footer {
            color: #888;
            font-size: 0.85em;
            margin-top: 40px;
            border-top: 1px solid #444;
            padding-top: 20px;
            text-align: center;
        }

        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://storage.googleapis.com/courtplay-storage/assets/Logo-Horizontal.png"
             alt="CourtPlay Logo" class="logo">

        <h2>Reset Your Password</h2>

        <p>Hi {{ $user->firstname }},</p>

        <p>We received a request to reset your password for your <strong>CourtPlay</strong> account.
        You can reset your password by clicking the button below:</p>

        <div class="center">
            <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        </div>

        <p>If you didn’t request this password reset, please ignore this email.
        This link will expire in <strong>60 minutes</strong> for your security.</p>

        <div class="footer">
            <p>© {{ date('Y') }} CourtPlay. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
