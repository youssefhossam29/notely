<head>
    <title>Error 504 (Gateway Timeout)!!</title>
</head>

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">504</div>
            <h1>Gateway Timeout</h1>
            <p>The server took too long to respond. Please try again later.</p>
            <div class="welcome-buttons">
                <a href="{{ route('my.notes') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
