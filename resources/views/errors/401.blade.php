<head>
    <title>Error 401 (Unauthorized)!!</title>
</head>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">401</div>
            <h1>Unauthorized</h1>
            <p>Oops! you're not authorized to view this page. Please log in or check your permissions.</p>
            <div class="welcome-buttons">
                <a href="{{ route('my.notes') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
