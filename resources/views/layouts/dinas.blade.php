{{-- resources/views/layouts/dinas.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard Dinas — SIPERKIM')</title>

  {{-- CSS utama dashboard dinas --}}
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dinas-verify.css') }}">

  {{-- JS untuk toggle sidebar --}}
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  @stack('styles')

  {{-- ✅ CSS notif dot (kalau belum ada di css utama) --}}
  <style>
    .menu-link {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      width:100%;
    }
    .notif-dot {
      min-width:18px;
      height:18px;
      padding:0 6px;
      border-radius:999px;
      background:#ef4444;
      color:#fff;
      font-size:11px;
      font-weight:800;
      line-height:18px;
      text-align:center;
      display:inline-flex;
      align-items:center;
      justify-content:center;
    }
  </style>
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

  <div id="wrapper" class="flex">
    {{-- SIDEBAR DINAS --}}
    <aside id="sidebar" class="sidebar" role="navigation" aria-label="Sidebar Dinas">
      <div class="sidebar-header">
        <img src="{{ asset('images/logo-siperkim.png') }}" class="sidebar-logo" alt="SIPERKIM">
        <h3 class="sidebar-title">
          Disperkimtan<br>
          <small>Kota Parepare</small>
        </h3>
      </div>

      <ul class="sidebar-menu">
        {{-- Dashboard --}}
        <li class="{{ request()->routeIs('dinas.dashboard') ? 'active' : '' }}">
          <a href="{{ route('dinas.dashboard') }}">Dashboard</a>
        </li>

        {{-- ✅ Verifikasi Data Perumahan + notif --}}
        <li class="{{ request()->routeIs('dinas.perumahan.verify.*') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('dinas.perumahan.verify.index') }}">
            <span>Verifikasi Data Perumahan</span>
            @if(($dinasPerumahanMasukCount ?? 0) > 0)
              <span class="notif-dot">{{ $dinasPerumahanMasukCount }}</span>
            @endif
          </a>
        </li>

        {{-- ✅ Verifikasi Permohonan Nota Dinas + notif --}}
        <li class="{{ request()->routeIs('dinas.permohonan.nota.*') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('dinas.permohonan.nota.index') }}">
            <span>Verifikasi Permohonan Nota Dinas</span>
            @if(($dinasNotaMasukCount ?? 0) > 0)
              <span class="notif-dot">{{ $dinasNotaMasukCount }}</span>
            @endif
          </a>
        </li>

        {{-- Verifikasi Penyerahan PSU (placeholder) --}}
        <li class="{{ request()->routeIs('dinas.permohonan.psu.*') ? 'active' : '' }}">
          @if(Route::has('dinas.permohonan.psu.index'))
            <a href="{{ route('dinas.permohonan.psu.index') }}">Verifikasi Penyerahan PSU</a>
          @else
            <a href="#">
              Verifikasi Penyerahan PSU
              <small style="font-size:10px;opacity:.7;">(segera)</small>
            </a>
          @endif
        </li>

        <li><a href="#">RTH - Penyiraman Otomatis</a></li>

        <li class="{{ request()->routeIs('dinas.settings.*') ? 'active' : '' }}">
          <a href="{{ route('dinas.settings.index') }}">Pengaturan</a>
        </li>

      </ul>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main id="content" class="content">
      <button
        id="sidebar-toggle"
        class="sidebar-toggle"
        aria-label="Toggle Sidebar"
        type="button"
      >
        ☰
      </button>

      @yield('content')
    </main>
  </div>

  @stack('scripts')
</body>
</html>