<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Permohonan ke Dinas — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <style>
    .status-pill {
      display: inline-flex;
      align-items: center;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
    }
    .status-pending   { background:#fef9c3; color:#854d0e; }
    .status-disetujui { background:#ecfdf3; color:#15803d; }
    .status-revisi    { background:#fef2f2; color:#b91c1c; }
    .status-ditolak   { background:#fee2e2; color:#b91c1c; }

    table.permohonan-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    table.permohonan-table th,
    table.permohonan-table td {
      padding: 8px 10px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
      vertical-align: middle;
    }
    table.permohonan-table th {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .05em;
      color: #6b7280;
    }

    .aksi-buttons {
      display: inline-flex;
      gap: 6px;
      flex-wrap: wrap;
    }
    .aksi-link {
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:5px 10px;
      border-radius:999px;
      font-size:11px;
      font-weight:600;
      text-decoration:none;
      border:1px solid #d1d5db;
      background:#ffffff;
      color:#1d4ed8;
      transition:background .15s, color .15s, border-color .15s;
    }
    .aksi-link:hover {
      background:#eff6ff;
      border-color:#93c5fd;
    }
    .aksi-link-secondary {
      color:#b45309;
      border-color:#fed7aa;
      background:#fffbeb;
    }
    .aksi-link-secondary:hover {
      background:#fef3c7;
      border-color:#fbbf24;
    }
  </style>
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

<div id="wrapper" class="flex">
  {{-- SIDEBAR --}}
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('images/logo-siperkim.png') }}" class="sidebar-logo" alt="SIPERKIM">
      <h3 class="sidebar-title">
        SIPERKIM<br>
        <small>Developer</small>
      </h3>
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
    <button id="sidebar-toggle" class="sidebar-toggle" type="button">☰</button>

    <div class="topbar">
      <div>
        <h1>Permohonan ke Dinas</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:4px;">
          Pilih jenis permohonan yang ingin diajukan dan pantau status permohonan Anda.
        </p>
      </div>
    </div>

    @if(session('status'))
      <div class="card" style="padding:10px 14px;margin-top:12px;background:#ecfdf3;border-left:4px solid #16a34a;font-size:13px;">
        {{ session('status') }}
      </div>
    @endif

    {{-- KARTU JENIS PERMOHONAN --}}
    <section class="grid-cards" style="margin-top:18px;">
      {{-- Kartu 1: Nota Dinas --}}
      <article class="house-card" style="max-width:480px;">
        <div class="house-body">
          <h3 class="house-title">Permohonan Nota Dinas Pembangunan Perumahan</h3>
          <p class="muted" style="margin-top:4px;">
            Pengajuan nota dinas / pengesahan site plan (rencana tapak) untuk proyek perumahan.
          </p>
          <div class="foot" style="margin-top:10px;justify-content:flex-end;">
            <a href="{{ route('developer.permohonan.nota.create') }}" class="btn-primary">
              Ajukan Nota Dinas
            </a>
          </div>
        </div>
      </article>

      {{-- Kartu 2: Penyerahan PSU (placeholder) --}}
      <article class="house-card" style="max-width:480px;opacity:0.6;">
        <div class="house-body">
          <h3 class="house-title">Permohonan Penyerahan PSU Perumahan</h3>
          <p class="muted" style="margin-top:4px;">
            Pengajuan serah terima prasarana, sarana, dan utilitas (PSU) perumahan kepada pemerintah.
          </p>
          <div class="foot" style="margin-top:10px;justify-content:flex-end;">
            <button class="btn-plain" type="button" disabled>Segera Hadir</button>
          </div>
        </div>
      </article>
    </section>

    {{-- RIWAYAT PERMOHONAN NOTA DINAS --}}
    <section class="card" style="margin-top:24px;padding:18px 20px;">
      <h2 style="font-size:14px;font-weight:700;margin-bottom:8px;">
        Riwayat Permohonan Nota Dinas Pembangunan Perumahan
      </h2>
      <p style="font-size:12px;color:#6b7280;margin-bottom:10px;">
        Berikut adalah daftar permohonan Nota Dinas yang telah Anda kirim ke Dinas.
      </p>

      @if(isset($notaDinasList) && $notaDinasList->isNotEmpty())
        <div style="overflow-x:auto;">
          <table class="permohonan-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Perumahan</th>
                <th>Tanggal Diajukan</th>
                <th>Status</th>
                <th>Catatan dari Dinas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notaDinasList as $idx => $permohonan)
                @php
                  $status = $permohonan->status ?? 'pending';
                  $label  = 'Pending';
                  $class  = 'status-pending';

                  if ($status === 'disetujui') {
                      $label = 'Disetujui';
                      $class = 'status-disetujui';
                  } elseif ($status === 'revisi') {
                      $label = 'Perlu Revisi';
                      $class = 'status-revisi';
                  } elseif ($status === 'ditolak') {
                      $label = 'Ditolak';
                      $class = 'status-ditolak';
                  }
                @endphp
                <tr>
                  <td>{{ $idx + 1 }}</td>
                  <td>
                    {{ $permohonan->nama_perumahan }}
                    <div style="font-size:11px;color:#6b7280;">
                      {{ $permohonan->nama_pengembang }}
                    </div>
                  </td>
                  <td>{{ $permohonan->created_at->format('d M Y H:i') }}</td>
                  <td>
                    <span class="status-pill {{ $class }}">{{ $label }}</span>
                  </td>
                  <td style="font-size:12px;color:#6b7280;">
                    {{ $permohonan->catatan_dinas ?? '-' }}
                  </td>
                  <td>
                    <div class="aksi-buttons">
                      {{-- DETAIL --}}
                      <a
                        href="{{ route('developer.permohonan.nota.show', $permohonan->id) }}"
                        class="aksi-link"
                      >
                        Detail
                      </a>

                      {{-- EDIT / PERBAIKI hanya jika status revisi --}}
                      @if($status === 'revisi')
                        <a
                          href="{{ route('developer.permohonan.nota.edit', $permohonan->id) }}"
                          class="aksi-link aksi-link-secondary"
                        >
                          Perbaiki
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <p style="font-size:13px;color:#6b7280;margin-top:4px;">
          Belum ada permohonan Nota Dinas yang dikirim. Silakan klik tombol
          <strong>“Ajukan Nota Dinas”</strong> untuk membuat permohonan baru.
        </p>
      @endif
    </section>
  </main>
</div>

</body>
</html>