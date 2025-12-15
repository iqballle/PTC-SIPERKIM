<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Developer — SIPERKIM</title>

  {{-- CSS di public --}}
  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  {{-- JS di resources via Vite --}}
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

@php
  /** @var \App\Models\User|null $user */
  $user = auth()->user();
@endphp

<div id="wrapper" class="flex">
  {{-- SIDEBAR --}}
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('images/logo-siperkim.png') }}" class="sidebar-logo" alt="SIPERKIM">
      <h3 class="sidebar-title">SIPERKIM<br><small>Developer</small></h3>
    </div>
  
    <ul class="sidebar-menu">
      {{-- Dashboard --}}
      <li class="{{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
        <a href="{{ route('developer.dashboard') }}">Dashboard</a>
      </li>
  
      {{-- Data Perumahan --}}
      <li class="{{ request()->routeIs('developer.perumahan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.perumahan.index') }}">Data Perumahan saya</a>
      </li>
  
      {{-- Permohonan ke Dinas --}}
      <li class="{{ request()->routeIs('developer.permohonan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.permohonan.index') }}">Permohonan Ke Dinas</a>
      </li>
  
      {{-- Notifikasi & Revisi --}}
      <li class="{{ request()->routeIs('developer.notifikasi.*') ? 'active' : '' }}">
        <a href="{{ route('developer.notifikasi.index') }}">
          Notifikasi & Revisi
  
          {{-- 🔴 TITIK MERAH / BADGE --}}
          @if(!empty($devRevisiCount) && $devRevisiCount > 0)
            <span class="notif-dot">{{ $devRevisiCount }}</span>
          @endif
        </a>
      </li>
  
      <li class="{{ request()->routeIs('developer.rth.*') ? 'active' : '' }}">
        <a href="{{ route('developer.rth.index') }}">RTH - Penyiraman Otomatis</a>
      </li>
      
      <li class="{{ request()->routeIs('developer.settings.*') ? 'active' : '' }}">
        <a href="{{ route('developer.settings.index') }}">Pengaturan</a>
      </li>
    </ul>
  </aside>
  

  {{-- KONTEN --}}
  <main id="content" class="content">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar">☰</button>

    {{-- TOP BAR --}}
    <div class="topbar">
      <h1>Dashboard</h1>
      <div class="topbar-right">
        <div class="user-pod">
          {{-- FOTO PROFIL: ambil dari accessor $user->photo_url (fallback ke default-avatar.png) --}}
          <img
            src="{{ $user?->photo_url ?? asset('images/default-avatar.png') }}"
            alt="Foto {{ $user?->name ?? 'Developer' }}"
          >
          <div>
            <div class="user-name">{{ $user?->name ?? 'Developer' }}</div>
            <div class="user-role">Developer</div>
          </div>
        </div>
      </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="kpis">
      <div class="kpi">
        <div class="kpi-title">Total Perumahan</div>
        <div class="kpi-sub">{{ $totalPerumahan }} lokasi terdaftar</div>
        <div class="kpi-num">{{ $totalPerumahan }}</div>
      </div>

      <div class="kpi">
        <div class="kpi-title">Permohonan ke Dinas</div>
        <div class="kpi-sub">{{ $totalPermohonan }} permohonan</div>
        <div class="kpi-num">{{ $totalPermohonan }}</div>
      </div>

      <div class="kpi">
        <div class="kpi-title">Perlu Revisi</div>
        <div class="kpi-sub">{{ $totalPerluRevisi }} permohonan</div>
        <div class="kpi-num">{{ $totalPerluRevisi }}</div>
      </div>
    </div>

    {{-- TABEL AKTIVITAS --}}
    <section class="card">
      <h2>Aktivitas Terbaru</h2>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Aktivitas</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($activities as $index => $act)
              @php
                $status = $act['status'] ?? 'pending';
                $badgeClass = 'badge-warn';
                $label = ucfirst($status);

                if ($status === 'disetujui') {
                    $badgeClass = 'badge-ok';
                    $label = 'Disetujui';
                } elseif ($status === 'revisi') {
                    $badgeClass = 'badge-bad';
                    $label = 'Perlu Revisi';
                } elseif ($status === 'ditolak') {
                    $badgeClass = 'badge-bad';
                    $label = 'Ditolak';
                } elseif ($status === 'pending') {
                    $badgeClass = 'badge-warn';
                    $label = 'Pending';
                }
              @endphp
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $act['keterangan'] }}</td>
                <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
                <td>
                  {{ optional($act['tanggal'])->timezone('Asia/Makassar')->format('d M Y H:i') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align:center;color:#6b7280;font-size:13px;">
                  Belum ada aktivitas terbaru.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

  </main>
</div>

</body>
</html>