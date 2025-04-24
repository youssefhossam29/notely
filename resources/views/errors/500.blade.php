<head>
    <title>Error 500 (Internal Server Error)!!</title>
</head>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">500</div>
            <h1>Internal Server Error</h1>
            <p>Oops! Something went wrong on our end. Please try again later.</p>
            <div class="welcome-buttons">
                <a href="{{ route('my.notes') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
