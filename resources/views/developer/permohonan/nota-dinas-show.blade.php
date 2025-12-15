{{-- resources/views/developer/permohonan/nota-dinas-show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Permohonan Nota Dinas — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .badge-status {
      display:inline-flex;
      align-items:center;
      padding:3px 10px;
      border-radius:999px;
      font-size:11px;
      font-weight:600;
    }
    .status-pending  { background:#fef9c3; color:#854d0e; }
    .status-disetujui { background:#ecfdf3; color:#15803d; }
    .status-revisi   { background:#fef2f2; color:#b91c1c; }
    .status-ditolak  { background:#fee2e2; color:#b91c1c; }

    .two-cols {
      display:grid;
      grid-template-columns:minmax(0, 1.1fr) minmax(0, 1fr);
      gap:20px;
      margin-top:12px;
    }
    @media (max-width: 960px){
      .two-cols { grid-template-columns:1fr; }
    }

    .info-table {
      width:100%;
      border-collapse:collapse;
      font-size:13px;
    }
    .info-table th,
    .info-table td {
      padding:6px 0;
      vertical-align:top;
    }
    .info-table th {
      width:32%;
      font-weight:600;
      color:#6b7280;
      padding-right:16px;
    }

    .doc-table {
      width:100%;
      border-collapse:collapse;
      font-size:13px;
      margin-top:6px;
    }
    .doc-table th,
    .doc-table td {
      padding:8px 10px;
      border-bottom:1px solid #e5e7eb;
      text-align:left;
    }
    .doc-table thead th {
      background:#f9fafb;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.05em;
      color:#6b7280;
    }

    .doc-link {
      font-size:12px;
      font-weight:500;
      color:#2563eb;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:4px;
      padding:4px 10px;
      border-radius:999px;
      background:#eff6ff;
    }
    .doc-link::before {
      content:"↗";
      font-size:11px;
    }
    .doc-link:hover {
      background:#dbeafe;
    }

    .doc-missing {
      display:inline-block;
      padding:2px 8px;
      border-radius:999px;
      font-size:11px;
      color:#9ca3af;
      background:#f3f4f6;
    }

    .section-title {
      font-size:14px;
      font-weight:700;
      margin:0 0 8px;
    }

    .card-header-actions {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      margin-bottom:8px;
    }

    .btn-plain-sm {
      display:inline-flex;
      align-items:center;
      gap:4px;
      padding:6px 10px;
      border-radius:999px;
      border:1px solid #e5e7eb;
      background:#ffffff;
      font-size:12px;
      font-weight:600;
      cursor:pointer;
      text-decoration:none;
      color:#374151;
    }
    .btn-plain-sm:hover {
      background:#f3f4f6;
    }
    .btn-primary-sm {
      display:inline-flex;
      align-items:center;
      gap:6px;
      padding:7px 12px;
      border-radius:999px;
      border:none;
      background:#5B7042;
      color:#ffffff;
      font-size:12px;
      font-weight:600;
      text-decoration:none;
      cursor:pointer;
    }
    .btn-primary-sm:hover {
      filter:brightness(1.05);
    }

  </style>
</head>
<body class="font-[Inter] antialiased text-[#2f2f2f]">

@php
  /** @var \App\Models\PermohonanNotaDinas $permohonan */
  $status = $permohonan->status ?? 'pending';
  $statusLabel = 'Pending';
  $statusClass = 'status-pending';

  if ($status === 'disetujui') {
      $statusLabel = 'Disetujui';
      $statusClass = 'status-disetujui';
  } elseif ($status === 'revisi') {
      $statusLabel = 'Perlu Revisi';
      $statusClass = 'status-revisi';
  } elseif ($status === 'ditolak') {
      $statusLabel = 'Ditolak';
      $statusClass = 'status-ditolak';
  }

  $docs = [
    ['field' => 'surat_permohonan',          'label' => 'Surat Permohonan'],
    ['field' => 'profil_perusahaan',        'label' => 'Profil Perusahaan'],
    ['field' => 'ktp_direktur',             'label' => 'KTP Direktur'],
    ['field' => 'npwp_perusahaan',          'label' => 'NPWP Perusahaan'],
    ['field' => 'akte_pendirian',           'label' => 'Akte Pendirian'],
    ['field' => 'surat_kesiapan_psu',       'label' => 'Surat Kesiapan PSU'],
    ['field' => 'surat_tidak_sengketa',     'label' => 'Surat Pernyataan Tidak Sengketa'],
    ['field' => 'pkkpr',                    'label' => 'PKKPR'],
    ['field' => 'nib_kbli',                 'label' => 'NIB & KBLI'],
    ['field' => 'peil_banjir',              'label' => 'Peil Banjir'],
    ['field' => 'alas_hak',                 'label' => 'Alas Hak Tanah'],
    ['field' => 'bast_tahap_pengembangan',  'label' => 'BAST Tahap Pengembangan'],
    ['field' => 'siteplan_a3',              'label' => 'Siteplan A3'],
    ['field' => 'peta_lokasi',              'label' => 'Peta Lokasi'],
    ['field' => 'site_plan',                'label' => 'Site Plan'],
    ['field' => 'kontur_tanah',             'label' => 'Kontur Tanah'],
    ['field' => 'rencana_jalan',            'label' => 'Rencana Jalan'],
    ['field' => 'rencana_drainase',         'label' => 'Rencana Drainase'],
    ['field' => 'rencana_rth',              'label' => 'Rencana RTH'],
    ['field' => 'rencana_air_bersih',       'label' => 'Rencana Air Bersih'],
    ['field' => 'rencana_sanitasi',         'label' => 'Rencana Sanitasi'],
    ['field' => 'rencana_fasum_fasos',      'label' => 'Rencana Fasum/Fasos'],
  ];
@endphp

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
      <li class="{{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
        <a href="{{ route('developer.dashboard') }}">Dashboard</a>
      </li>

      <li class="{{ request()->routeIs('developer.perumahan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.perumahan.index') }}">Data Perumahan saya</a>
      </li>

      <li class="{{ request()->routeIs('developer.permohonan.*') ? 'active' : '' }}">
        <a href="{{ route('developer.permohonan.index') }}">Permohonan Ke Dinas</a>
      </li>

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
    <button id="sidebar-toggle" class="sidebar-toggle" type="button" aria-label="Toggle Sidebar">☰</button>

    {{-- TOP TITLE --}}
    <div class="topbar">
      <div>
        <h1 style="margin-bottom:4px;">Detail Permohonan Nota Dinas</h1>
        <p style="font-size:13px;color:#6b7280;margin:0;">
          Lihat ringkasan data permohonan dan status dokumen yang telah diunggah.
        </p>
      </div>

      <div style="text-align:right;">
        <div class="badge-status {{ $statusClass }}">{{ $statusLabel }}</div>
        <div style="font-size:11px;color:#6b7280;margin-top:4px;">
          Diajukan pada: {{ $permohonan->created_at->format('d M Y H:i') }} WITA
          @if($permohonan->verified_at)
            <br>Terakhir diverifikasi: {{ $permohonan->verified_at->format('d M Y H:i') }} WITA
          @endif
        </div>
      </div>
    </div>

    {{-- LAYOUT 2 KOLOM: IDENTITAS & RINGKASAN --}}
    <section class="two-cols">
      {{-- KARTU IDENTITAS --}}
      <article class="card">
        <div class="card-header-actions">
          <h2 class="section-title">Identitas Permohonan</h2>

          {{-- Tombol kembali --}}
          <a href="{{ route('developer.permohonan.index') }}" class="btn-plain-sm">
            ← Kembali ke daftar
          </a>
        </div>

        <table class="info-table">
          <tr>
            <th>Nama Perumahan</th>
            <td>{{ $permohonan->nama_perumahan }}</td>
          </tr>
          <tr>
            <th>Nama Pengembang</th>
            <td>{{ $permohonan->nama_pengembang }}</td>
          </tr>
          <tr>
            <th>No. Telepon</th>
            <td>{{ $permohonan->telepon ?? '-' }}</td>
          </tr>
          <tr>
            <th>Alamat Perumahan</th>
            <td>{{ $permohonan->alamat_perumahan }}</td>
          </tr>
          <tr>
            <th>Kelurahan / Kecamatan</th>
            <td>{{ $permohonan->kelurahan }} / {{ $permohonan->kecamatan }}</td>
          </tr>
          <tr>
            <th>Keterangan Tambahan</th>
            <td>{{ $permohonan->keterangan_tambahan ?? '-' }}</td>
          </tr>
        </table>
      </article>

      {{-- KARTU RINGKASAN & CATATAN --}}
      <article class="card">
        <h2 class="section-title">Ringkasan Status</h2>

        <p style="font-size:13px;color:#4b5563;margin-top:0;">
          Status permohonan:
          <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </p>

        <div style="font-size:12px;color:#6b7280;margin-top:6px;">
          <strong>ID Permohonan:</strong> #{{ $permohonan->id }}<br>
          @if($permohonan->perumahan)
            <strong>Perumahan Terkait:</strong> {{ $permohonan->perumahan->nama }}
          @endif
        </div>

        <div style="margin-top:14px;">
          <h3 style="font-size:13px;font-weight:600;margin-bottom:6px;">Catatan dari Dinas</h3>
          <div style="font-size:13px;color:#374151;background:#f9fafb;border-radius:8px;padding:10px 12px;border:1px solid #e5e7eb;min-height:60px;">
            {{ $permohonan->catatan_dinas ?? 'Belum ada catatan khusus dari Dinas.' }}
          </div>
        </div>

        {{-- Tombol Perbaiki hanya jika status REVISI --}}
        @if($permohonan->status === 'revisi')
          <div style="margin-top:16px;">
            <a href="{{ route('developer.permohonan.nota.edit', $permohonan->id) }}" class="btn-primary-sm">
              ✏️ Perbaiki & Unggah Ulang Dokumen
            </a>
          </div>
        @endif
      </article>
    </section>

    {{-- KARTU DOKUMEN --}}
    <section class="card" style="margin-top:22px;">
      <div class="card-header-actions">
        <h2 class="section-title">Dokumen yang Diunggah</h2>
        {{-- opsional: tombol menuju halaman edit kalau status revisi --}}
        @if($permohonan->status === 'revisi')
          <a href="{{ route('developer.permohonan.nota.edit', $permohonan->id) }}" class="btn-primary-sm">
            Kelola Dokumen
          </a>
        @endif
      </div>

      <p style="font-size:12px;color:#6b7280;margin-top:0;margin-bottom:8px;">
        Dokumen yang belum diunggah akan ditandai sebagai <strong>"Belum diunggah"</strong>.
      </p>

      <div style="overflow-x:auto;margin-top:4px;">
        <table class="doc-table">
          <thead>
            <tr>
              <th style="width:40px;">No</th>
              <th>Nama Dokumen</th>
              <th style="width:180px;">Status</th>
              <th style="width:150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($docs as $i => $doc)
              @php
                $field = $doc['field'];
                $label = $doc['label'];
                $path  = $permohonan->{$field} ?? null;
                $hasFile = !empty($path);
              @endphp
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $label }}</td>
                <td>
                  @if($hasFile)
                    <span class="badge-status status-disetujui">Sudah diunggah</span>
                  @else
                    <span class="doc-missing">Belum diunggah</span>
                  @endif
                </td>
                <td>
                  @if($hasFile)
                    <a href="{{ asset('storage/' . $path) }}" target="_blank" class="doc-link">
                      Lihat Dokumen
                    </a>
                  @else
                    <span style="font-size:11px;color:#9ca3af;">Tidak ada file</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>

  </main>
</div>

</body>
</html>