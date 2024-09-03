<!DOCTYPE html>
<html>
<head>
    <style>
        .email-body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            padding-bottom: 20px;
        }
        .email-title {
            font-size: 24px;
            color: #0F622D;
            margin-bottom: 10px;
        }
        .email-content {
            margin-bottom: 20px;
        }
        .email-content p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .email-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #0F622D;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body class="email-body">
    <div class="email-container">
        <div class="email-header">
            <div class="email-title">ITam Invitational Sportfest</div>
        </div>

        <div class="email-content">
            <p>Hello,</p>
            <p>We are excited to have you join us from Fit Family!</p>
            <p>Please click the button below to verify your email address.</p>
            <a href="{{ $actionUrl }}" class="email-button">Verify Email Address</a>
            <p>If you did not create an account, no further action is required.</p>
            <p>Regards,<br>ITam_Invitational_Sportfest Committe Team</p>
        </div>

        <div class="email-footer">
            © 2024 ITam Invitational Sportfest. All rights reserved.
        </div>
    </div>
</body>
</html>
