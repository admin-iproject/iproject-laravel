<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired</title>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 48px;
            max-width: 500px;
            text-align: center;
        }
        h1 {
            font-size: 72px;
            margin: 0 0 16px 0;
            color: #667eea;
        }
        h2 {
            font-size: 24px;
            margin: 0 0 16px 0;
            color: #1f2937;
        }
        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 32px 0;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>419</h1>
        <h2>Session Expired</h2>
        <p>Your session has expired due to inactivity. For your security, please log in again to continue.</p>
        <a href="{{ route('login') }}" class="btn">Return to Login</a>
    </div>
</body>
</html>