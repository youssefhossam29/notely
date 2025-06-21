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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="{{ asset('/uploads/logo/rounded_notely.png') }}" type="image/png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        .note-title {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5em;
        }

        .note-content {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card {
            min-height: 220px;
        }

        .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .note-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .note-content {
            font-size: 0.9rem;
        }

        .note-footer {
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .btn-container {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .btn-container>* {
                width: 100% !important;
            }

            .btn {
                font-size: 0.75rem;
                padding: 0.3rem 0.4rem;
            }
        }

        @media (min-width: 992px) and (max-width: 1299.98px) {
            .custom-cols>.col {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }

            .card {
                min-height: 160px;
            }
        }

        .toggle-pin-btn{
            border: none;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <footer class="footer bg-white border-b border-gray-100 text-black text-center py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <span>&copy; 2025 Notes, All rights reserved. Powered by Youssef Hossam</span>
            <div class="social-icons">
                <a href="https://www.linkedin.com/in/youssefhossameldin29/" target="_blank" aria-label="linkedin"
                    style="text-decoration:none"><i
                        class="fa-brands fa-linkedin-in fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <a href="http://wa.me/+201279932792" target="_blank" aria-label="Facebook"
                    style="text-decoration:none"><i class="fab fa-whatsapp fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;</a>
            </div>
        </div>
    </footer>

</body>

</html>
