<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CourtPlay')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top px-4 py-3 navbar-scroll">
        <div class="container-fluid">
            {{-- Logo kiri --}}
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <img src="{{ asset('assets/Logo Horizontal.svg') }}" alt="CourtPlay Logo" height="45" class="me-2">
            </a>

            {{-- Toggler di kanan (hamburger menu) --}}
            <button class="navbar-toggler custom-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvasRight">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Menu utama & kanan untuk layar besar --}}
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
                {{-- Menu tengah --}}
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About Us</a></li>
                </ul>

                {{-- Menu kanan --}}
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item">
                        <a class="btn btn-custom px-3 ms-lg-2" href="{{ route('signup') }}">Sign up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- OFFCANVAS (Sidebar Kanan untuk Mobile) --}}
    <div class="offcanvas offcanvas-end sidebar-nav" tabindex="-1" id="navbarOffcanvasRight">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn border-0 p-0 close-icon" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="bi bi-x-lg text-primary-500"></i>
            </button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <ul class="navbar-nav text-center">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About Us</a></li>
            </ul>

            <div class="border-top pt-3">
                <a class="btn btn-custom w-100 mt-2" href="{{ route('login') }}">Login</a>
                <a class="btn btn-custom w-100 mt-2" href="{{ route('signup') }}">Sign up</a>
            </div>
        </div>
    </div>




    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
