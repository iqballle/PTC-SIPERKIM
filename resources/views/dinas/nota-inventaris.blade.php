@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventaris Nota Dinas — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dinas-verify.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] antialiased text-[#111827]">

<div id="wrapper" class="flex">
  {{-- SIDEBAR --}}
  <aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
      <img src="{{ asset('images/logo-siperkim.png') }}" class="sidebar-logo" alt="SIPERKIM">
      <h3 class="sidebar-title">
        Disperkimtan<br>
        <small>Kota Parepare</small>
      </h3>
    </div>
    <ul class="sidebar-menu">
      <li><a href="{{ route('dinas.dashboard') }}">Dashboard</a></li>
      <li><a href="{{ route('dinas.perumahan.verify.index') }}">Verifikasi Data Perumahan</a></li>
      <li class="{{ request()->routeIs('dinas.permohonan.nota.*') ? 'active' : '' }}">
        <a href="{{ route('dinas.permohonan.nota.index') }}">Permohonan Nota Dinas</a>
      </li>
      <li><a href="#">RTH - Penyiraman Otomatis</a></li>
      <li><a href="#">Pengaturan</a></li>
    </ul>
  </aside>

  {{-- KONTEN --}}
  <main id="content" class="content">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar" type="button">☰</button>

    <div class="topbar">
      <div>
        <h1>Inventaris Nota Dinas Perumahan</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:4px;">
          Daftar Nota Dinas yang sudah disetujui, dikelompokkan per permohonan/perumahan.
        </p>
      </div>
      <div class="topbar-right">
        <a href="{{ route('dinas.permohonan.nota.index') }}" class="btn-plain">
          ← Kembali ke Permohonan Masuk
        </a>
      </div>
    </div>

    {{-- TAB kecil di dalam halaman --}}
    <section class="card" style="margin-bottom:14px;padding:8px 12px;">
      <div style="display:flex;gap:8px;font-size:13px;">
        <a href="{{ route('dinas.permohonan.nota.index') }}"
           class="tab-link {{ request()->routeIs('dinas.permohonan.nota.index') ? 'active' : '' }}">
          Permohonan Masuk
        </a>
        <a href="{{ route('dinas.permohonan.nota.inventaris') }}"
           class="tab-link {{ request()->routeIs('dinas.permohonan.nota.inventaris') ? 'active' : '' }}">
          Inventaris Nota Dinas
        </a>
      </div>
    </section>

    {{-- FILTER PENCARIAN --}}
    <section class="card" style="margin-bottom:16px;padding:12px 16px;">
      <form method="GET" action="{{ route('dinas.permohonan.nota.inventaris') }}"
            style="display:flex;flex-wrap:wrap;gap:8px;align-items:flex-end;font-size:13px;">
        <div style="flex:1 1 220px;">
          <label class="block text-xs font-semibold text-gray-600 mb-1">
            Cari Perumahan / Pengembang
          </label>
          <input type="text" name="q" value="{{ request('q') }}" class="input" placeholder="Nama perumahan atau pengembang">
        </div>
        <div style="width:140px;">
          <label class="block text-xs font-semibold text-gray-600 mb-1">
            Tahun
          </label>
          <input type="number" name="tahun" value="{{ request('tahun') }}" class="input" placeholder="2025">
        </div>
        <div>
          <button type="submit" class="btn-primary">
            Filter
          </button>
        </div>
      </form>
    </section>

    {{-- TABEL INVENTARIS --}}
    <section class="card" style="padding:14px 18px;">
      <div class="table-responsive">
        <table class="table">
          <thead>
          <tr>
            <th style="width:40px;">No</th>
            <th>Perumahan</th>
            <th>Developer</th>
            <th>Tanggal Disetujui</th>
            <th>Catatan Dinas</th>
            <th style="width:120px;">Aksi</th>
          </tr>
          </thead>
          <tbody>
          @forelse($items as $idx => $row)
            @php
              $verifiedAt = $row->verified_at
                  ? (\Carbon\Carbon::parse($row->verified_at)->format('d M Y'))
                  : '-';
            @endphp
            <tr>
              <td>{{ $items->firstItem() + $idx }}</td>
              <td>
                <div style="font-weight:600;font-size:13px;">
                  {{ $row->nama_perumahan ?? ($row->perumahan->nama ?? '-') }}
                </div>
                <div style="font-size:11px;color:#6b7280;">
                  ID Permohonan: #{{ $row->id }}
                </div>
              </td>
              <td>{{ $row->nama_pengembang ?? ($row->user->name ?? '-') }}</td>
              <td>{{ $verifiedAt }}</td>
              <td style="font-size:12px;color:#374151;max-width:260px;">
                {{ $row->catatan_dinas ? Str::limit($row->catatan_dinas, 80) : '-' }}
              </td>
              <td>
                <a href="{{ route('dinas.permohonan.nota.show', $row->id) }}"
                   class="btn-plain" style="font-size:12px;">
                  Lihat Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center;font-size:13px;color:#6b7280;">
                Belum ada Nota Dinas yang disetujui.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div style="margin-top:10px;">
        {{ $items->links() }}
      </div>
    </section>
  </main>
</div>

</body>
</html>
