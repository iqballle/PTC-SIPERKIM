<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Perumahan — {{ $perumahan->nama }} | SIPERKIM</title>

  {{-- CSS utama dashboard developer --}}
  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color: #2f2f2f;
    }

    .detail-layout {
      display: grid;
      grid-template-columns: 1.4fr 1fr;
      gap: 24px;
    }

    @media (max-width: 960px) {
      .detail-layout {
        grid-template-columns: 1fr;
      }
    }

    .detail-cover {
      border-radius: 14px;
      overflow: hidden;
      background: #f3f4f6;
    }

    .detail-cover img {
      width: 100%;
      max-height: 260px;
      object-fit: cover;
      display: block;
    }

    .detail-thumbs {
      margin-top: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .detail-thumb {
      width: 72px;
      height: 56px;
      border-radius: 8px;
      overflow: hidden;
      background: #e5e7eb;
      cursor: pointer;
      border: 1px solid #e5e7eb;
      transition: border-color 0.15s ease, transform 0.15s ease;
    }

    .detail-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .detail-thumb:hover {
      border-color: #9C2F21;
      transform: translateY(-1px);
    }

    .info-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .info-table th,
    .info-table td {
      padding: 6px 0;
      vertical-align: top;
    }

    .info-table th {
      width: 38%;
      font-weight: 600;
      color: #6b7280;
      padding-right: 12px;
    }

    .info-table td {
      color: #111827;
    }

    .section-title {
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: 0.02em;
    }

    .section-sub {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 8px;
    }

    .badge-status {
      display: inline-flex;
      align-items: center;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-status-ok {
      background: #ecfdf3;
      color: #15803d;
    }

    .badge-status-pending {
      background: #fef9c3;
      color: #854d0e;
    }

    .badge-status-revisi {
      background: #fef2f2;
      color: #b91c1c;
    }

    .badge-status-ditolak {
      background: #fee2e2;
      color: #b91c1c;
    }

    .spec-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px 24px;
      font-size: 13px;
    }

    @media (max-width: 768px) {
      .spec-grid {
        grid-template-columns: 1fr;
      }
    }

    .spec-item {
      padding: 4px 0;
      border-bottom: 1px solid #f3f4f6;
    }

    .doc-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 8px;
    }

    @media (max-width: 768px) {
      .doc-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    .doc-item {
      border-radius: 10px;
      overflow: hidden;
      background: #f3f4f6;
      border: 1px solid #e5e7eb;
      cursor: pointer;
    }

    .doc-item img {
      width: 100%;
      height: 80px;
      object-fit: cover;
      display: block;
    }

    /* Modal zoom gambar */
    .image-modal {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .image-modal-inner {
      position: relative;
      max-width: 90vw;
      max-height: 90vh;
    }

    .image-modal-inner img {
      max-width: 100%;
      max-height: 90vh;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
      display: block;
    }

    .image-modal-close {
      position: absolute;
      top: -10px;
      right: -10px;
      width: 32px;
      height: 32px;
      border-radius: 999px;
      border: none;
      background: #111827;
      color: #f9fafb;
      font-size: 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .is-hidden {
      display: none !important;
    }

    .revisi-card {
      margin-top: 10px;
      padding: 10px 12px;
      border-radius: 10px;
      background: #fef2f2;
      border: 1px solid #fecaca;
      font-size: 12px;
      color: #7f1d1d;
    }
    .revisi-card strong {
      display: block;
      margin-bottom: 4px;
      font-size: 12px;
      color: #b91c1c;
    }
  </style>
</head>
<body>

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

    {{-- TOPBAR --}}
    <div class="topbar">
      <div>
        <h1>Detail Perumahan</h1>
        <p style="font-size:12px;color:#6b7280;margin-top:2px;">
          Periksa data yang sudah diajukan ke dinas dan status verifikasinya.
        </p>
      </div>
      <div class="topbar-right" style="gap:8px;">
        <a href="{{ route('developer.perumahan.index') }}" class="btn-plain">← Kembali</a>
        <a href="{{ route('developer.perumahan.edit', $perumahan) }}" class="btn-primary">Edit Perumahan</a>
      </div>
    </div>

    {{-- ISI DETAIL --}}
    <section class="card" style="padding:20px 24px;">
      <div class="detail-layout">

        {{-- ================= KOLOM KIRI: GAMBAR & SPESIFIKASI ================= --}}
        <div>

          {{-- COVER + GALERI --}}
          @php
              $coverUrl  = $perumahan->cover_url ?? asset('images/placeholder-house.jpg');
              $gallery   = $perumahan->gallery_urls ?? [];
              // hilangkan cover dari gallery kalau sama
              $gallery   = array_values(array_filter($gallery, fn($g) => $g !== $coverUrl));
          @endphp

          <div class="detail-cover">
            <img src="{{ $coverUrl }}" alt="Cover {{ $perumahan->nama }}" class="zoomable">
          </div>

          @if(!empty($gallery))
            <div class="detail-thumbs">
              @foreach($gallery as $url)
                <button type="button" class="detail-thumb">
                  <img src="{{ $url }}" alt="Foto {{ $perumahan->nama }}" class="zoomable">
                </button>
              @endforeach
            </div>
          @endif

          {{-- SPESIFIKASI RINGKAS --}}
          <div style="margin-top:18px;">
            <div class="section-title">Spesifikasi Ringkas</div>
            <p class="section-sub">Informasi teknis utama dari perumahan ini.</p>

            <div class="spec-grid">
              <div class="spec-item">
                <strong>Tipe Rumah</strong><br>
                {{ $perumahan->tipe ?? '-' }}
              </div>
              <div class="spec-item">
                <strong>Status Unit</strong><br>
                {{ $perumahan->status_unit ?? 'Tersedia' }}
              </div>
              <div class="spec-item">
                <strong>Luas Tanah</strong><br>
                {{ $perumahan->luas_tanah ?? '-' }} m²
              </div>
              <div class="spec-item">
                <strong>Luas Bangunan</strong><br>
                {{ $perumahan->luas_bangunan ?? '-' }} m²
              </div>
              <div class="spec-item">
                <strong>Jumlah Unit</strong><br>
                {{ $perumahan->jumlah_unit ?? '-' }}
              </div>
              <div class="spec-item">
                <strong>Tahun Pembangunan</strong><br>
                {{ $perumahan->tahun_pembangunan ?? '-' }}
              </div>
            </div>
          </div>

          {{-- SPESIFIKASI DETAIL (DARI FIELD "spesifikasi") --}}
          @php
              $spesRaw = $perumahan->spesifikasi ?? null;
              $spesList = [];
              if ($spesRaw) {
                  $parts = preg_split('/\r\n|\r|\n|,/', $spesRaw);
                  $spesList = array_values(array_filter(array_map('trim', $parts)));
              }
          @endphp

          @if($spesRaw)
            <div style="margin-top:18px;">
              <div class="section-title">Spesifikasi Detail</div>
              <p class="section-sub">Poin spesifikasi bangunan berdasarkan input developer.</p>

              <div class="spec-grid">
                @foreach($spesList as $item)
                  <div class="spec-item">{{ $item }}</div>
                @endforeach
              </div>
            </div>
          @endif

          {{-- TABEL PERKIRAAN & ANGSURAN --}}
          @if(!empty($perumahan->tabel_angsuran_url ?? null))
            <div style="margin-top:22px;">
              <div class="section-title">Tabel Perkiraan & Angsuran</div>
              <p class="section-sub">
                Gambar tabel simulasi harga jual dan angsuran yang diunggah oleh developer.
              </p>
              <div style="border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;background:#f9fafb;">
                <img src="{{ $perumahan->tabel_angsuran_url }}"
                     alt="Tabel Angsuran {{ $perumahan->nama }}"
                     class="zoomable"
                     style="width:100%;max-height:320px;object-fit:contain;background:#fff;">
              </div>
            </div>
          @endif

          {{-- DENAH + DOKUMEN LAINNYA (IMB, Sertifikat, dll) --}}
          <div style="margin-top:22px;display:grid;grid-template-columns: minmax(0,1.1fr) minmax(0,1fr);gap:18px;">
            @if(!empty($perumahan->denah_url ?? null))
              <div>
                <div class="section-title">Denah Rumah</div>
                <p class="section-sub">Denah tampak atas yang diunggah oleh developer.</p>
                <div style="border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;background:#f9fafb;">
                  <img src="{{ $perumahan->denah_url }}"
                       alt="Denah {{ $perumahan->nama }}"
                       class="zoomable"
                       style="width:100%;max-height:260px;object-fit:contain;background:#fff;">
                </div>
              </div>
            @endif

            @php
                $dokumenUrls = $perumahan->dokumen_foto_urls ?? [];
            @endphp

            @if(!empty($dokumenUrls))
              <div>
                <div class="section-title">Informasi Perumahan Lainnya</div>
                <p class="section-sub">
                  Misalnya foto IMB, sertifikat, atau dokumen legalitas lain.
                </p>
                <div class="doc-grid">
                  @foreach($dokumenUrls as $url)
                    <button type="button" class="doc-item">
                      <img src="{{ $url }}" alt="Dokumen {{ $perumahan->nama }}" class="zoomable">
                    </button>
                  @endforeach
                </div>
              </div>
            @endif
          </div>

        </div>

        {{-- ================= KOLOM KANAN: INFO UTAMA ================= --}}
        <div>
          <div class="section-title">Informasi Perumahan</div>
          <p class="section-sub">
            Ringkasan data utama perumahan yang Anda ajukan.
          </p>

          {{-- STATUS VERIFIKASI --}}
          <div style="margin-bottom:16px;">
            @php
              $rawStatus  = $perumahan->status ?? 'pending';
              $hasRevisi  = !empty($perumahan->catatan_revisi ?? null);

              if ($rawStatus === 'disetujui') {
                  $badgeClass = 'badge-status-ok';
                  $label      = 'Disetujui Dinas';
              } elseif ($rawStatus === 'pending' && $hasRevisi) {
                  $badgeClass = 'badge-status-revisi';
                  $label      = 'Perlu Revisi dari Dinas';
              } elseif ($rawStatus === 'ditolak') {
                  $badgeClass = 'badge-status-ditolak';
                  $label      = 'Ditolak';
              } else {
                  $badgeClass = 'badge-status-pending';
                  $label      = 'Pending (menunggu verifikasi)';
              }
            @endphp

            <span class="badge-status {{ $badgeClass }}">
              Status: {{ $label }}
            </span>

            @if($perumahan->approved_at)
              <div style="font-size:11px;color:#6b7280;margin-top:4px;">
                Disetujui pada {{ $perumahan->approved_at->format('d M Y H:i') }}
                @if($perumahan->approved_by)
                  {{-- approved_by bisa berupa ID user dinas --}}
                  oleh Petugas Dinas
                @endif
              </div>
            @endif
          </div>

          {{-- JIKA ADA CATATAN REVISI DARI DINAS --}}
          @if(!empty($perumahan->catatan_revisi))
            <div class="revisi-card">
              <strong>Catatan Revisi dari Dinas</strong>
              {!! nl2br(e($perumahan->catatan_revisi)) !!}
            </div>
          @endif

          {{-- TABEL INFO --}}
          <table class="info-table" style="margin-top:16px;">
            <tr>
              <th>Nama Perumahan</th>
              <td>{{ $perumahan->nama }}</td>
            </tr>
            <tr>
              <th>Nama Perusahaan</th>
              <td>{{ $perumahan->nama_perusahaan ?? '-' }}</td>
            </tr>
            <tr>
              <th>Lokasi / Alamat</th>
              <td>{{ $perumahan->lokasi }}</td>
            </tr>
            <tr>
              <th>Harga Mulai</th>
              <td>
                @if($perumahan->harga)
                  Rp {{ number_format($perumahan->harga, 0, ',', '.') }}
                @else
                  -
                @endif
              </td>
            </tr>
            <tr>
              <th>Tipe Rumah</th>
              <td>{{ $perumahan->tipe ?? '-' }}</td>
            </tr>
            <tr>
              <th>Status Unit</th>
              <td>{{ $perumahan->status_unit ?? 'Tersedia' }}</td>
            </tr>
            <tr>
              <th>Telepon</th>
              <td>{{ $perumahan->telepon ?? '-' }}</td>
            </tr>
            <tr>
              <th>Fasilitas Utama</th>
              <td>
                @if($perumahan->fasilitas)
                  {!! nl2br(e($perumahan->fasilitas)) !!}
                @else
                  -
                @endif
              </td>
            </tr>
          </table>

          {{-- DESKRIPSI --}}
          @if($perumahan->deskripsi)
            <div style="margin-top:18px;">
              <div class="section-title">Deskripsi</div>
              <p style="font-size:13px;line-height:1.6;color:#374151;">
                {!! nl2br(e($perumahan->deskripsi)) !!}
              </p>
            </div>
          @endif

          {{-- MAP MINI --}}
          @if(!empty($perumahan->lokasi_google_map))
            <div style="margin-top:18px;">
              <div class="section-title">Lokasi di Google Maps</div>
              <div style="border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;background:#f3f4f6;height:220px;">
                <iframe
                  src="{{ $perumahan->lokasi_google_map }}"
                  width="100%"
                  height="100%"
                  style="border:0;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>
            </div>
          @endif

        </div>
      </div>
    </section>
  </main>
</div>

{{-- MODAL ZOOM GAMBAR --}}
<div id="imageModal" class="image-modal is-hidden">
  <div class="image-modal-inner">
    <button type="button" class="image-modal-close" id="imageModalClose">&times;</button>
    <img src="" alt="Preview Gambar" id="imageModalImage">
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal      = document.getElementById('imageModal');
    const modalImg   = document.getElementById('imageModalImage');
    const modalClose = document.getElementById('imageModalClose');

    if (!modal || !modalImg || !modalClose) return;

    // Semua gambar yang bisa di-zoom
    const zoomables = document.querySelectorAll('img.zoomable');

    zoomables.forEach(function (img) {
      img.style.cursor = 'zoom-in';
      img.addEventListener('click', function () {
        const src = img.getAttribute('src');
        if (!src) return;
        modalImg.setAttribute('src', src);
        modal.classList.remove('is-hidden');
      });
    });

    function closeModal() {
      modal.classList.add('is-hidden');
      modalImg.setAttribute('src', '');
    }

    modalClose.addEventListener('click', function (e) {
      e.stopPropagation();
      closeModal();
    });

    modal.addEventListener('click', function (e) {
      // klik area gelap di luar gambar untuk menutup
      if (e.target === modal) {
        closeModal();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !modal.classList.contains('is-hidden')) {
        closeModal();
      }
    });
  });
</script>

</body>
</html>
