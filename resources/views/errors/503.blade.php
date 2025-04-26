@section('title', 'Error 503 (Service Unavailable)!!')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">503</div>
            <h1>Service Unavailable</h1>
            <p>Service temporarily unavailable. Please check back soon.</p>
            <div class="welcome-buttons">
                <a href="{{ route('my.notes') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
