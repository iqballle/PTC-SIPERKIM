<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notifikasi & Revisi — SIPERKIM Developer</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .notif-section-title { font-size: 14px; font-weight: 700; margin-bottom: 6px; }
    .notif-empty { font-size: 13px; color: #9ca3af; }

    .notif-badge{
      display:inline-flex; padding:2px 8px; border-radius:999px;
      font-size:11px; font-weight:600;
    }
    .notif-badge-perumahan{ background:#eff6ff; color:#1d4ed8; }
    .notif-badge-nota{ background:#fef3c7; color:#92400e; }

    .notif-tag-revisi{
      display:inline-flex; padding:2px 8px; border-radius:999px;
      background:#fef2f2; color:#b91c1c; font-size:11px; font-weight:600; margin-left:6px;
    }
    .notif-meta { font-size:11px; color:#9ca3af; margin-top:2px; }

    /* Badge titik merah di sidebar */
    .menu-link{
      display:flex; align-items:center; justify-content:space-between; gap:10px;
    }
    .notif-dot{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:18px; height:18px; padding:0 6px;
      border-radius:999px;
      background:#ef4444; color:#fff;
      font-size:11px; font-weight:700;
      line-height:1;
    }

    .btn-small{
      
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

    
  </style>
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
        <a class="menu-link" href="{{ route('developer.notifikasi.index') }}">
          <span>Notifikasi & Revisi</span>
          @if(!empty($revisiCount) && $revisiCount > 0)
            <span class="notif-dot">{{ $revisiCount }}</span>
          @endif
        </a>
      </li>

      {{-- RTH --}}
      <li class="{{ request()->routeIs('developer.rth.*') ? 'active' : '' }}">
        <a href="{{ route('developer.rth.index') }}">RTH - Penyiraman Otomatis</a>
      </li>

      {{-- Pengaturan --}}
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
        <h1>Notifikasi & Revisi</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:4px;">
          Ringkasan perumahan dan permohonan yang sedang memerlukan revisi dari Anda.
        </p>
      </div>
    </div>

    {{-- SECTION: PERUMAHAN PERLU REVISI --}}
    <section class="card" style="padding:16px 18px;margin-bottom:16px;">
      <div class="notif-section-title">Perumahan Perlu Revisi</div>

      @if($perumahanRevisi->isEmpty())
        <p class="notif-empty">Tidak ada perumahan yang memerlukan revisi saat ini.</p>
      @else
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Perumahan</th>
                <th>Status</th>
                <th>Catatan Dinas</th>
                <th>Terakhir Diupdate</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($perumahanRevisi as $index => $p)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    {{ $p->nama ?? '-' }}<br>
                    <span class="notif-meta">{{ $p->lokasi ?? '' }}</span>
                  </td>
                  <td>
                    <span class="notif-badge notif-badge-perumahan">Perumahan</span>
                    <span class="notif-tag-revisi">Perlu Revisi</span>
                  </td>
                  <td style="max-width:260px;">
                    <span style="font-size:13px;color:#374151;">
                      {{-- ✅ perbaikan: catatan revisi --}}
                      {{ $p->catatan_revisi ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <span class="notif-meta">
                      {{ optional($p->updated_at)->format('d M Y H:i') }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('developer.perumahan.edit', $p->id) }}" class="btn-small">
                      Perbaiki Data
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    {{-- SECTION: PERMOHONAN NOTA DINAS PERLU REVISI --}}
    <section class="card" style="padding:16px 18px;margin-bottom:16px;">
      <div class="notif-section-title">Permohonan Nota Dinas Perlu Revisi</div>

      @if($notaRevisi->isEmpty())
        <p class="notif-empty">Tidak ada permohonan Nota Dinas yang memerlukan revisi saat ini.</p>
      @else
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Perumahan</th>
                <th>Status</th>
                <th>Catatan Dinas</th>
                <th>Terakhir Diupdate</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notaRevisi as $index => $n)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    {{ $n->nama_perumahan }}<br>
                    <span class="notif-meta">
                      {{ $n->alamat_perumahan }},
                      {{ $n->kelurahan }},
                      {{ $n->kecamatan }}
                    </span>
                  </td>
                  <td>
                    <span class="notif-badge notif-badge-nota">Nota Dinas</span>
                    <span class="notif-tag-revisi">Perlu Revisi</span>
                  </td>
                  <td style="max-width:260px;">
                    <span style="font-size:13px;color:#374151;">
                      {{ $n->catatan_dinas ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <span class="notif-meta">
                      {{ optional($n->updated_at)->format('d M Y H:i') }}
                    </span>
                  </td>
                  <td style="display:flex;gap:8px;align-items:center;">
                    {{-- ✅ langsung ke edit revisi --}}
                    <a href="{{ route('developer.permohonan.nota.edit', $n->id) }}" class="btn-small">
                      Perbaiki
                    </a>
                    <a href="{{ route('developer.permohonan.nota.show', $n->id) }}" class="btn-small">
                      Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    {{-- SECTION: PERUMAHAN DISETUJUI (BARU) --}}
    <section class="card" style="padding:16px 18px;margin-bottom:16px;">
      <div class="notif-section-title">Perumahan Disetujui</div>

      @if(empty($perumahanDisetujui) || $perumahanDisetujui->isEmpty())
        <p class="notif-empty">Belum ada perumahan yang disetujui.</p>
      @else
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Perumahan</th>
                <th>Status</th>
                <th>Diproses</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($perumahanDisetujui as $i => $p)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>
                    {{ $p->nama ?? '-' }}<br>
                    <span class="notif-meta">{{ $p->lokasi ?? '' }}</span>
                  </td>
                  <td>
                    <span class="notif-badge notif-badge-perumahan">Perumahan</span>
                    <span class="notif-badge" style="background:#ecfdf3;color:#15803d;margin-left:6px;">
                      Disetujui
                    </span>
                  </td>
                  <td class="notif-meta">
                    {{ optional($p->approved_at)->format('d M Y H:i') ?? '-' }}
                  </td>
                  <td>
                    <a href="{{ route('developer.perumahan.show', $p->id) }}" class="btn-small">
                      Lihat Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    {{-- SECTION: NOTA DINAS DISETUJUI (OPSIONAL) --}}
    <section class="card" style="padding:16px 18px;">
      <div class="notif-section-title">Permohonan Nota Dinas Disetujui</div>

      @if(empty($notaDisetujui) || $notaDisetujui->isEmpty())
        <p class="notif-empty">Belum ada permohonan Nota Dinas yang disetujui.</p>
      @else
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Perumahan</th>
                <th>Diproses</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notaDisetujui as $i => $n)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $n->nama_perumahan }}</td>
                  <td class="notif-meta">
                    {{ optional($n->verified_at)->format('d M Y H:i') ?? '-' }}
                  </td>
                  <td>
                    <a href="{{ route('developer.permohonan.nota.show', $n->id) }}" class="btn-small">
                      Lihat Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

  </main>
</div>

</body>
</html>