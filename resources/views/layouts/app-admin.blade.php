<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PJRVZT7CVP"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-PJRVZT7CVP');
    </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin')</title>

  {{-- Bootstrap & Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Admin CSS only (jangan load app.css di layout ini) --}}
  <link rel="stylesheet" href="{{ asset('css/appadmin.css') }}">
  <link rel="icon" href="{{ asset('assets/Logo.svg') }}">
  @stack('styles')
</head>
<body data-bs-theme="dark" class="admin-body">
    <div class="main flex-grow-1 d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-lg-none">
        <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay" height="28">
            <span>Admin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">Projects</a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">News</a></li>
            <li><a class="nav-link" href="{{ route('analytics') }}">Site</a></li>
            <li><hr class="dropdown-divider bg-secondary"></li>
            <li>
                <a class="nav-link text-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
                </a>
            </li>
            </ul>
        </div>
        </div>
    </nav>
    </div>

  <div class="admin-shell d-flex">
    {{-- ✅ Sidebar (Desktop Only) --}}
    <aside class="sidebar d-none d-lg-flex flex-column" id="adminSidebar">
      <div class="brand">
        <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay">
        <div class="title">Admin</div>
      </div>

      <div class="nav-sec">MAIN</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
      </a>

      <div class="nav-sec">MANAGE</div>
      <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i><span>Users</span>
      </a>
      <a href="{{ route('admin.projects.index') }}" class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <i class="bi bi-collection"></i><span>Projects</span>
      </a>
      <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <i class="bi bi-newspaper"></i><span>News</span>
      </a>
      <a href="{{ route('analytics') }}" class="nav-item">
        <i class="bi bi-house"></i><span>Site</span>
      </a>

      <a href="{{ url('/docs/api') }}" target="_blank" class="nav-item">
        <i class="bi bi-journal-code"></i>
        <span class="hide-on-mini">API Docs</span>
    </a>
        <a href="{{ route('logout') }}" class="nav-item text-danger mt-1"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i><span class="hide-on-mini">Logout</span>
        </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </aside>

    {{-- ✅ Main Content --}}
    <div class="main flex-grow-1 d-flex flex-column">
      <header class="topbar">
        <div class="page-title">@yield('page_title','Dashboard')</div>
        <div class="d-flex align-items-center gap-2">
          <span class="text-white-300 d-none d-md-inline">
            Hello, {{ optional(auth()->user())->first_name ?? 'Admin' }}
          </span>
          <i class="bi bi-person-circle fs-5"></i>
        </div>
      </header>

      <main class="content">
        @yield('content')
      </main>
    </div>
  </div>

  {{-- Bootstrap JS --}}
<script>
(function() {
  const sb   = document.getElementById('adminSidebar');
  const btn  = document.getElementById('sbToggle');
  const key  = 'cp_admin_sidebar_mini';
  const TRANSITION_DELAY = 250; // Waktu tunggu setelah transisi CSS 0.2s (200ms)

  try { if (localStorage.getItem(key) === '1') sb.classList.add('mini'); } catch(e){}

  btn?.addEventListener('click', (e) => {
    e.preventDefault();
    sb.classList.toggle('mini');
    try { localStorage.setItem(key, sb.classList.contains('mini') ? '1' : '0'); } catch(e){}

    setTimeout(() => {
        if (window.projectsTrendChart) {

            // 🔥 PERBAIKAN: Paksa Reflow/Layout Update 🔥
            // Panggil offsetWidth elemen canvas. Ini memaksa browser untuk menghitung
            // lebar saat ini (yang sudah berubah akibat sidebar) sebelum memanggil resize.
            const canvas = document.getElementById('projectsTrend');
            if (canvas) {
                // Baris ini penting! Memaksa browser untuk menghitung ulang tata letak.
                // eslint-disable-next-line no-unused-expressions
                canvas.offsetWidth;
            }

            // Panggil metode resize Chart.js
            window.projectsTrendChart.resize();
        }
    }, TRANSITION_DELAY);
  });

  document.querySelector('main')?.classList.add('fade-in');
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
