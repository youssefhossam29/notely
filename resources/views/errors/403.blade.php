@section('title', 'Error 403 (Forbidden)!!')

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="error-code">403</div>
            <h1>Forbidden</h1>
            <p>Oops! you don't have permission to access this page.</p>
            <div class="welcome-buttons">
                <a href="{{ route('notes.index') }}" class="btn btn-light loginbtn">Go Back Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>
