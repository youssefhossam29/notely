@section('title', 'Error 429 (Too Many Requests)!!')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">429</div>
            <h1>Too Many Requests</h1>
            <p>You've sent too many requests. Please wait a moment and try again.</p>
            <div class="welcome-buttons">
                <a href="{{ route('notes.index') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
