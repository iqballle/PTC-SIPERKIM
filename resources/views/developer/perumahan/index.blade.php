<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Perumahan Saya — SIPERKIM</title>

  {{-- CSS utama dashboard developer + CSS khusus halaman ini --}}
  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">

  {{-- JS toggle sidebar (resources/js) --}}
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

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

      {{-- TOPBAR SEDERHANA --}}
      <div class="topbar">
        <h1>Data Perumahan Saya</h1>
        <div class="topbar-right">
          <a href="{{ route('developer.perumahan.create') }}" class="btn-primary">+ Tambah Perumahan</a>
        </div>
      </div>

      {{-- FILTERS --}}
      <section class="filters card">
        <form class="filters-grid" method="GET" action="{{ route('developer.perumahan.index') }}">
          {{-- Cari nama perumahan --}}
          <input
            type="text"
            name="q"
            class="input"
            placeholder="Cari nama perumahan…"
            value="{{ request('q') }}"
          >

          {{-- Filter status --}}
          <select name="status" class="input">
            <option value="">Semua Status</option>
            <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Pending</option>
            <option value="revisi"    {{ request('status')=='revisi'    ? 'selected' : '' }}>Revisi</option>
          </select>

          {{-- Tombol filter --}}
          <button class="btn-plain" type="submit">Filter</button>

          {{-- Tombol reset filter --}}
          <a href="{{ route('developer.perumahan.index') }}" class="btn-plain" style="text-align:center;">
            Reset
          </a>
        </form>
      </section>

      {{-- LIST / GRID --}}
      @if($projects->isEmpty())
        <section class="empty card">
          <img src="{{ asset('images/empty-state.svg') }}" alt="" />
          <h3>Belum ada perumahan</h3>
          <p>Tambah perumahan pertama Anda untuk mulai mengajukan verifikasi.</p>
          <a href="{{ route('developer.perumahan.create') }}" class="btn-primary">Tambah Perumahan</a>
        </section>
      @else
        <section class="grid-cards">
          @foreach($projects as $p)
            @php
              // Thumbnail: cover → foto pertama gallery → placeholder
              $thumb = $p->cover_url
                        ?? ($p->gallery_urls[0] ?? null)
                        ?? asset('images/placeholder-house.jpg');

              $harga = $p->harga
                        ? 'Rp ' . number_format($p->harga, 0, ',', '.')
                        : '-';

              // Logika status untuk developer
              $rawStatus = $p->status ?? 'pending';
              $hasRevisi = !empty($p->catatan_revisi ?? null);

              if ($rawStatus === 'disetujui') {
                  $labelStatus = 'Disetujui';
                  $badgeClass  = 'badge-ok';     // hijau
              } elseif ($rawStatus === 'pending' && $hasRevisi) {
                  $labelStatus = 'Perlu Revisi';
                  $badgeClass  = 'badge-bad';    // merah
              } else {
                  $labelStatus = 'Pending';
                  $badgeClass  = 'badge-warn';   // kuning
              }
            @endphp

            <article class="house-card">
              <div class="thumb" style="background-image:url('{{ $thumb }}')"></div>

              <div class="house-body">
                <h3 class="house-title">{{ $p->nama }}</h3>
                <div class="muted">{{ $p->lokasi }}</div>

                <div class="meta">
                  <span>Tipe {{ $p->tipe ?? '-' }}</span>
                  <span class="dot">•</span>
                  <span>{{ $harga }}</span>
                </div>

                <div class="foot">
                  <span class="badge {{ $badgeClass }}">
                    {{ $labelStatus }}
                  </span>

                  <div class="actions">
                    {{-- DETAIL --}}
                    <a href="{{ route('developer.perumahan.show', $p->id) }}" class="btn-link">
                      Detail
                    </a>

                    {{-- EDIT --}}
                    <a href="{{ route('developer.perumahan.edit', $p->id) }}" class="btn-link">
                      Edit
                    </a>
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </section>
      @endif
    </main>
  </div>

</body>
</html>
