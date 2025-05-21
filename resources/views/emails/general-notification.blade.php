<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #333;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            max-width: 600px;
            margin: auto;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
        h2 {
            color: #1a202c;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>{{ $subjectText }}</h2>
        <p>{{ $messageText }}</p>

        <div class="footer">
            {{ __('email.footer.sent_by', ['app' => config('app.name')]) }}<br>
            {{ __('email.footer.timestamp', ['date' => now()->format('F j, Y - H:i')]) }}
        </div>
    </div>
</body>
</html>
