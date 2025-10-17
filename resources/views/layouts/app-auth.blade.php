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
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg sticky-top px-4 py-3 navbar-scroll">
        <div class="container-fluid">
            {{-- Logo kiri --}}
            <a class="navbar-brand fw-bold" href="{{ route('analytics') }}">
                <img src="{{ asset('assets/Logo Horizontal.svg') }}" alt="CourtPlay Logo" height="45" class="me-2">
            </a>

            {{-- Toggler kanan (hamburger) --}}
            <button class="navbar-toggler border-0 text-primary-500" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarAuth">
                <i class="bi bi-list fs-2 text-primary-500"></i>
            </button>

            {{-- Menu utama (desktop) --}}
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item" style="padding-left: 3rem"><a class="nav-link" href="{{ route('analytics') }}">Analytics</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('videos.index') }}">Uploads</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('plan') }}">Plan</a></li>
                </ul>
            </div>

            {{-- Menu kanan (desktop) --}}
            <ul class="navbar-nav ms-auto align-items-center d-none d-lg-flex">
                {{-- Halo user --}}
                <li class="nav-item me-2">
                    <span class="nav-link fw-semibold text-primary-500">Hello, {{ Auth::user()->first_name }}</span>
                </li>

                {{-- Icon dropdown user --}}
                <li class="nav-item dropdown">
                    <a class="nav-link p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-3 text-primary-500"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow user-menu">
                        <li>
                            <a class="dropdown-item fw-semibold" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    {{-- OFFCANVAS (sidebar kanan untuk mobile) --}}
    <div class="offcanvas offcanvas-end sidebar-nav" tabindex="-1" id="offcanvasNavbarAuth">
        <div class="offcanvas-header justify-content-end">
            <button type="button" class="btn border-0 p-0 close-icon" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="bi bi-x-lg fs-4 text-primary-500"></i>
            </button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between">
            {{-- Menu Tengah --}}
            <ul class="navbar-nav text-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('analytics') }}">Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('videos.index') }}">Uploads</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('plan') }}">Plan</a></li>
                <li class="nav-item"><a href="{{ route('profile') }}" class="nav-link">Profile</a>
                </li>
            </ul>

            {{-- Menu Bawah --}}
            <div class="border-top pt-3 text-center">
                <span class="nav-link fw-semibold text-primary-500 mb-2 d-block">Hello, {{ Auth::user()->first_name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-custom w-100">Logout</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
