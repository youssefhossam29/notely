@section('title', 'Error 404 (Not Found)!!')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">403</div>
            <h1>Page Not Found</h1>
            <p>Oops! The page you're looking for doesn't exist or has been moved.</p>
            <div class="welcome-buttons">
                <a href="{{ route('notes.index') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
