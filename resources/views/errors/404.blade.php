<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 404 (Not Found)!!</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .error-code {
            font-size: 72px;
            color: #4F46E5;
            margin: 0 0 20px;
        }
        .error-title {
            font-size: 24px;
            color: #1F2937;
            margin: 0 0 10px;
        }
        .error-message {
            color: #6B7280;
            margin-bottom: 30px;
        }
        .home-button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .home-button:hover {
            background: #4338CA;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            Oops! The page you're looking for doesn't exist or has been moved.
        </p>
        <a href="{{ route('my.notes') }}" class="home-button">Go Back Home</a>
    </div>
</body>
</html>
