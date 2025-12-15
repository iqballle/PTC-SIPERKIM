{{-- resources/views/developer/permohonan/nota-dinas-edit.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perbaiki Permohonan Nota Dinas — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .two-cols {
      display:grid;
      grid-template-columns:minmax(0, 1.1fr) minmax(0, 1fr);
      gap:20px;
      margin-top:12px;
    }
    @media (max-width: 960px){
      .two-cols { grid-template-columns:1fr; }
    }

    .section-title {
      font-size:14px;
      font-weight:700;
      margin:0 0 10px;
    }

    .form-field {
      margin-bottom:12px;
    }
    .form-label {
      display:block;
      font-size:13px;
      font-weight:600;
      margin-bottom:4px;
    }
    .form-input,
    .form-textarea {
      width:100%;
      border-radius:8px;
      border:1px solid #d1d5db;
      padding:9px 11px;
      font-size:13px;
      outline:none;
      background:#ffffff;
      transition:border-color .15s, box-shadow .15s;
    }
    .form-input:focus,
    .form-textarea:focus {
      border-color:#5B7042;
      box-shadow:0 0 0 1px rgba(91,112,66,0.25);
    }
    .form-input[disabled] {
      background:#f9fafb;
      color:#6b7280;
    }
    .form-textarea {
      min-height:70px;
      resize:vertical;
    }

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
      vertical-align:middle;
    }
    .doc-table thead th {
      background:#f9fafb;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:.05em;
      color:#6b7280;
    }

    .doc-link {
      font-size:11px;
      font-weight:500;
      color:#2563eb;
      text-decoration:none;
      display:inline-flex;
      align-items:center;
      gap:4px;
      padding:3px 8px;
      border-radius:999px;
      background:#eff6ff;
      margin-top:4px;
    }
    .doc-link::before {
      content:"↗";
      font-size:10px;
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

    .file-input {
      font-size:12px;
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
      justify-content:center;
      gap:6px;
      padding:8px 14px;
      border-radius:999px;
      border:none;
      background:#5B7042;
      color:#ffffff;
      font-size:13px;
      font-weight:600;
      cursor:pointer;
    }
    .btn-primary-sm:hover {
      filter:brightness(1.05);
    }

    .hint {
      font-size:11px;
      color:#6b7280;
      margin-top:2px;
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

    {{-- TOPBAR --}}
    <div class="topbar">
      <div>
        <h1 style="margin-bottom:4px;">Perbaiki Permohonan Nota Dinas</h1>
        <p style="font-size:13px;color:#6b7280;margin:0;">
          Silakan perbaiki data dan dokumen sesuai catatan dari Dinas, lalu kirim ulang.
        </p>
      </div>

      <div style="text-align:right;">
        <div class="badge-status {{ $statusClass }}">{{ $statusLabel }}</div>
        <div style="font-size:11px;color:#6b7280;margin-top:4px;">
          ID Permohonan: #{{ $permohonan->id }}<br>
          Diajukan: {{ $permohonan->created_at->format('d M Y H:i') }} WITA
        </div>
      </div>
    </div>

    @if($permohonan->catatan_dinas)
      <section class="card" style="margin-bottom:16px;">
        <div class="section-title" style="margin-bottom:6px;">Catatan dari Dinas</div>
        <p style="font-size:13px;color:#374151;margin:0;">
          {{ $permohonan->catatan_dinas }}
        </p>
      </section>
    @endif

    {{-- FORM EDIT --}}
    <form
      method="POST"
      action="{{ route('developer.permohonan.nota.update', $permohonan->id) }}"
      enctype="multipart/form-data"
    >
      @csrf
      @method('PUT')

      <section class="two-cols">
        {{-- KOLOM KIRI: DATA TEKS --}}
        <article class="card">
          <h2 class="section-title">Data Permohonan</h2>

          {{-- Nama Perumahan (dibaca saja, tidak diubah) --}}
          <div class="form-field">
            <label class="form-label">Nama Perumahan</label>
            <input
              type="text"
              class="form-input"
              value="{{ $permohonan->nama_perumahan }}"
              disabled
            >
            <input type="hidden" name="perumahan_id" value="{{ $permohonan->perumahan_id }}">
          </div>

          {{-- Nama Pengembang --}}
          <div class="form-field">
            <label class="form-label" for="nama_pengembang">Nama Pengembang</label>
            <input
              id="nama_pengembang"
              type="text"
              name="nama_pengembang"
              class="form-input"
              value="{{ old('nama_pengembang', $permohonan->nama_pengembang) }}"
              required
            >
          </div>

          {{-- Telepon --}}
          <div class="form-field">
            <label class="form-label" for="telepon">No. Telepon</label>
            <input
              id="telepon"
              type="text"
              name="telepon"
              class="form-input"
              value="{{ old('telepon', $permohonan->telepon) }}"
              placeholder="Masukkan nomor telepon yang aktif"
            >
          </div>

          {{-- Alamat --}}
          <div class="form-field">
            <label class="form-label" for="alamat_perumahan">Alamat Perumahan</label>
            <textarea
              id="alamat_perumahan"
              name="alamat_perumahan"
              class="form-textarea"
              required
            >{{ old('alamat_perumahan', $permohonan->alamat_perumahan) }}</textarea>
          </div>

          {{-- Kelurahan & Kecamatan --}}
          <div class="form-field">
            <label class="form-label" for="kelurahan">Kelurahan</label>
            <input
              id="kelurahan"
              type="text"
              name="kelurahan"
              class="form-input"
              value="{{ old('kelurahan', $permohonan->kelurahan) }}"
              required
            >
          </div>

          <div class="form-field">
            <label class="form-label" for="kecamatan">Kecamatan</label>
            <input
              id="kecamatan"
              type="text"
              name="kecamatan"
              class="form-input"
              value="{{ old('kecamatan', $permohonan->kecamatan) }}"
              required
            >
          </div>

          {{-- Keterangan Tambahan --}}
          <div class="form-field">
            <label class="form-label" for="keterangan_tambahan">Keterangan Tambahan (opsional)</label>
            <textarea
              id="keterangan_tambahan"
              name="keterangan_tambahan"
              class="form-textarea"
              placeholder="Tambahkan informasi lain jika diperlukan"
            >{{ old('keterangan_tambahan', $permohonan->keterangan_tambahan) }}</textarea>
          </div>
        </article>

        {{-- KOLOM KANAN: DOKUMEN --}}
        <article class="card">
          <div class="section-title">Perbaikan Dokumen</div>
          <p class="hint">
            Jika ada dokumen yang diminta untuk diperbaiki, unggah file revisi pada kolom di bawah.
            Kosongkan jika tidak ada perubahan pada dokumen tersebut.
          </p>

          <div style="margin-top:10px;max-height:420px;overflow-y:auto;">
            <table class="doc-table">
              <thead>
                <tr>
                  <th style="width:30px;">#</th>
                  <th>Dokumen</th>
                  <th style="width:160px;">Upload Revisi</th>
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
                    <td>
                      {{ $label }}
                      <br>
                      @if($hasFile)
                        <a href="{{ asset('storage/'.$path) }}" target="_blank" class="doc-link">
                          Lihat file lama
                        </a>
                      @else
                        <span class="doc-missing">Belum diunggah</span>
                      @endif
                    </td>
                    <td>
                      <input
                        type="file"
                        name="{{ $field }}"
                        class="file-input"
                        accept=".pdf,.jpg,.jpeg,.png"
                      >
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </article>
      </section>

      {{-- AKSI BAWAH --}}
      <section class="card" style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;gap:10px;">
        <a href="{{ route('developer.permohonan.nota.show', $permohonan->id) }}" class="btn-plain-sm">
          ← Batal & kembali ke detail
        </a>

        <button type="submit" class="btn-primary-sm">
          💾 Simpan Perbaikan & Kirim Ulang
        </button>
      </section>
    </form>
  </main>
</div>

</body>
</html>