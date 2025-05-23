<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @hasSection('title')
                @yield('title') | {{ config('app.name', 'Notely') }}
            @else
                {{ config('app.name', 'Notely') }}
            @endif
        </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .inner-container {
                background: white;
                padding: 25px;
                border-radius: 10px;
                text-align: start;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                margin-top: 25px;
            }

            .main-container{
                justify-content: center;
                align-items: center;
            }

            .welcome-section {
                align-items: center;
                justify-content: center;
                background-size: cover;
                background-position: center;
                text-align: center;
            }

            .welcome-content {
                background-color: rgba(255, 255, 255, 0.9);
                padding: 5px;
                border-radius: 10px;
            }

            .welcome-content h1 {
                font-size: 2rem;
                margin-bottom: 20px;
            }

            .welcome-content p {
                font-size: 1rem;
                margin-bottom: 30px;
            }

            .welcome-buttons a {
                margin: 5px;
                padding: 10px 20px;
                font-size: 1rem;
                border-radius: 5px;
            }

            .loginbtn{
                background-color:#1E2738;
                color:white;
                border: #1E2738 solid 1px;
            }

            .error-code {
                font-size: 62px;
                color: #1E2738;
                margin: 0 0 20px;
                font-weight: bold;
            }

            .input-wrapper {
                position: relative;
            }

            .toggle-password {
                position: absolute;
                right: 10px;
                top: 45px;
                transform: translateY(-50%);
                cursor: pointer;
                font-size: 18px;
                color: #1f2937;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 main-container">
            <div>
                <a href="/">
                    <img src="/uploads/logo/notely.png" width="120px">
                </a>
            </div>

            <div class="inner-container" style="margin-bottom: 50px;">
                {{ $slot }}
            </div>
        </div>

        <script>
            function togglePassword() {
                const passwordInput = document.getElementById("password");
                const toggleIcon = document.getElementById("toggleIcon");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
                }
            }
        </script>
    </body>
</html>
