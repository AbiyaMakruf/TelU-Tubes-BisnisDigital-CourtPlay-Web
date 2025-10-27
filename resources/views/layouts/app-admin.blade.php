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
  <div class="admin-shell">
    {{-- Sidebar --}}
    <aside class="sidebar" id="adminSidebar">
      <div class="brand">
        <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay">
        <div class="title hide-on-mini">Admin</div>
      </div>

      <button type="button" class="collapse-btn mb-3" id="sbToggle">
        <span class="hide-on-mini">Collapse</span>
        <i class="bi bi-chevron-left hide-on-mini"></i>
        <i class="bi bi-list mini-only"></i>
      </button>

      <div class="nav-sec hide-on-mini">MAIN</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i><span class="hide-on-mini">Dashboard</span>
      </a>

      <div class="nav-sec hide-on-mini">MANAGE</div>
      <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i><span class="hide-on-mini">Users</span>
      </a>
      <a href="{{ route('admin.projects.index') }}" class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <i class="bi bi-collection"></i><span class="hide-on-mini">Projects</span>
      </a>
      <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <i class="bi bi-newspaper"></i><span class="hide-on-mini">News</span>
      </a>
      <a href="{{ route('logout') }}" class="nav-item"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span class="hide-on-mini">Logout</span>
      </a>

        {{-- Hidden logout form --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

      <div class="nav-footer">
        <div class="nav-sec hide-on-mini">SITE</div>
        <a href="{{ route('analytics') }}" class="nav-item">
          <i class="bi bi-house"></i><span class="hide-on-mini">Site</span>
        </a>

      </div>
    </aside>

    {{-- Right pane --}}
    <div class="main flex-grow-1 d-flex flex-column">
      <header class="topbar">
        <div class="page-title">@yield('page_title','Dashboard')</div>
        <div class="d-flex align-items-center gap-2">
          <span class="text-white-300 d-none d-md-inline">Hello, {{ auth()->user()->first_name ?? 'Admin' }}</span>
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

  @stack('scripts')
</body>
</html>
