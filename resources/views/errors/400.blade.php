<head>
    <title>Error 400 (Bad Request)!!</title>
</head>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">400</div>
            <h1>Bad Request</h1>
            <p>Oops! Something went wrong with your request. Please try again.</p>
            <div class="welcome-buttons">
                <a href="{{ route('my.notes') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
