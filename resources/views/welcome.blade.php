@auth
    <script>
        window.location.href = "{{ route('my.notes') }}";
    </script>
@endauth


<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="welcome-section">
        <div class="welcome-content">
            <h1>Welcome to Notely</h1>
            <p>Your secure and seamless note-taking experience.</p>
            <div class="welcome-buttons">
                <a href="{{ route('login') }}" class="btn btn-light loginbtn">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Register</a>
            </div>
        </div>
    </div>
</x-guest-layout>
