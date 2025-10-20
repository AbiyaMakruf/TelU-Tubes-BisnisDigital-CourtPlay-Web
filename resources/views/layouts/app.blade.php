<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','CourtPlay')</title>

  <!-- Vendor -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- App -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo.svg') }}">

  <style>
    .navbar .nav-link.active { color: var(--primary-500, #a3ce14)!important; font-weight:600; }
    .btn.btn-custom { background: var(--primary-500, #a3ce14); color:#111; border:0; }
    .btn.btn-custom:hover { filter:brightness(1.05); }
  </style>

  @stack('styles')
</head>
<body>

  {{-- NAVBAR universal (guest & auth) --}}
  <nav class="navbar navbar-expand-lg sticky-top px-4 py-3 navbar-scroll">
    <div class="container-fluid">
      {{-- Brand: guest -> home, auth -> analytics --}}
      <a class="navbar-brand fw-bold" href="@auth {{ route('analytics') }} @else {{ route('home') }} @endauth">
        <img src="{{ asset('assets/Logo Horizontal.svg') }}" alt="CourtPlay Logo" height="45" class="me-2">
      </a>

      {{-- Toggler (mobile) --}}
      <button class="navbar-toggler border-0 text-primary-500" type="button"
              data-bs-toggle="offcanvas" data-bs-target="#appOffcanvasNav"
              aria-controls="appOffcanvasNav" aria-label="Toggle navigation">
        <i class="bi bi-list fs-2 text-primary-500"></i>
      </button>

      {{-- Menu tengah (desktop) --}}
      <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
        <ul class="navbar-nav mx-auto">

          {{-- AUTH menu --}}
          @auth
            <li class="nav-item" style="padding-left:3rem">
              <a class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}"
                 href="{{ route('analytics') }}">Analytics</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('videos.*') ? 'active' : '' }}"
                 href="{{ route('videos.index') }}">Uploads</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('plan') ? 'active' : '' }}"
                 href="{{ route('plan') }}">Plan</a>
            </li>
          @endauth

          {{-- GUEST menu --}}
          @guest
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                 href="{{ route('home') }}">Features</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}"
                 href="{{ route('pricing') }}">Pricing</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                 href="{{ route('about') }}">About Us</a>
            </li>
          @endguest

          {{-- Umum untuk semua --}}
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}"
               href="{{ route('news.index') }}">News</a>
          </li>
        </ul>
      </div>

      {{-- Menu kanan (desktop) --}}
      <ul class="navbar-nav ms-auto align-items-center d-none d-lg-flex">
        @auth
          <li class="nav-item me-2">
            <span class="nav-link fw-semibold text-primary-500">
              Hello, {{ \Illuminate\Support\Str::limit(auth()->user()->first_name ?? 'User', 18) }}
            </span>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle fs-3 text-primary-500"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow user-menu" aria-labelledby="userDropdown">
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
        @endauth

        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item">
            <a class="btn btn-custom px-3 ms-lg-2" href="{{ route('signup.form') }}">Sign up</a>
          </li>
        @endguest
      </ul>
    </div>
  </nav>

  {{-- OFFCANVAS (mobile) --}}
  <div class="offcanvas offcanvas-end sidebar-nav" tabindex="-1" id="appOffcanvasNav" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header justify-content-end">
      <button type="button" class="btn border-0 p-0 close-icon" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="bi bi-x-lg fs-4 text-primary-500"></i>
      </button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between">
      <ul class="navbar-nav text-center">
        {{-- AUTH --}}
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('analytics') }}">Analytics</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('videos.index') }}">Uploads</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('plan') }}">Plan</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profile</a></li>
        @endauth

        {{-- GUEST --}}
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('pricing') }}">Pricing</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
        @endguest

        {{-- Umum --}}
        <li class="nav-item"><a class="nav-link" href="{{ route('news.index') }}">News</a></li>
      </ul>

      <div class="border-top pt-3 text-center">
        @auth
          <span class="nav-link fw-semibold text-primary-500 mb-2 d-block">
            Hello, {{ \Illuminate\Support\Str::limit(auth()->user()->first_name ?? 'User', 18) }}
          </span>
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-custom w-100">Logout</button>
          </form>
        @endauth

        @guest
          <a class="btn btn-custom w-100 mt-2" href="{{ route('login') }}">Login</a>
          <a class="btn btn-custom w-100 mt-2" href="{{ route('signup.form') }}">Sign up</a>
        @endguest
      </div>
    </div>
  </div>

  {{-- CONTENT --}}
  <main>
    @yield('content')
  </main>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
