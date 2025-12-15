@extends('layouts.dinas')

@section('title', 'Detail Perumahan — '.$perumahan->nama.' | SIPERKIM Dinas')

@section('content')
  {{-- ====== STYLE KHUSUS HALAMAN DETAIL PERUMAHAN DINAS ====== --}}
  <style>
    .detail-layout {
      display: grid;
      grid-template-columns: 1.4fr 1fr;
      gap: 24px;
    }
    @media (max-width: 960px) {
      .detail-layout { grid-template-columns: 1fr; }
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
    .info-table td { color: #111827; }

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
      .spec-grid { grid-template-columns: 1fr; }
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
      .doc-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
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

    .btn-primary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 8px 16px;
      border-radius: 999px;
      border: none;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      background: #16a34a;      /* hijau */
      color: #ffffff;
      transition: filter .15s ease;
    }
    .btn-primary:hover {
      filter: brightness(1.05);
    }
  </style>

  {{-- TOPBAR --}}
  @php
    // Status tab tujuan ketika klik "kembali"
    $backStatus = ($perumahan->status === 'pending' && !empty($perumahan->catatan_revisi))
        ? 'revisi'
        : ($perumahan->status ?? 'pending');
  @endphp

  <div class="topbar">
    <div>
      <h1>Detail Perumahan (Verifikasi)</h1>
      <p style="font-size:12px;color:#6b7280;margin-top:2px;">
        Dinas dapat memeriksa detail perumahan sebelum menyetujui atau memberi status revisi.
      </p>
    </div>

    <div class="topbar-right" style="gap:8px;">
      <a href="{{ route('dinas.perumahan.verify.index', ['status' => $backStatus]) }}"
         class="btn-plain">
        ← Kembali ke daftar
      </a>

      {{-- Tombol SETUJUI (hanya kalau belum disetujui) --}}
      @if($perumahan->status !== 'disetujui')
        <form method="POST"
              action="{{ route('dinas.perumahan.approve', $perumahan->id) }}"
              onsubmit="return confirm('Setujui perumahan ini?');">
          @csrf
          <button type="submit" class="btn-primary">
            Setujui Perumahan
          </button>
        </form>
      @endif
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
          $spesRaw  = $perumahan->spesifikasi ?? null;
          $spesList = [];
          if ($spesRaw) {
              $parts    = preg_split('/\r\n|\r|\n|,/', $spesRaw);
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

        {{-- DENAH + DOKUMEN LAINNYA --}}
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

      {{-- ================= KOLOM KANAN: INFO UTAMA UNTUK DINAS ================= --}}
      <div>
        <div class="section-title">Informasi Perumahan</div>
        <p class="section-sub">
          Ringkasan data utama perumahan yang diajukan oleh developer.
        </p>

        {{-- STATUS VERIFIKASI --}}
        <div style="margin-bottom:16px;">
          @php
            $status    = $perumahan->status ?? 'pending';
            $hasRevisi = !empty($perumahan->catatan_revisi);

            $badgeClass = 'badge-status-pending';
            $label      = 'Pending';

            if ($status === 'disetujui') {
                $badgeClass = 'badge-status-ok';
                $label      = 'Disetujui';
            } elseif ($status === 'pending' && $hasRevisi) {
                $badgeClass = 'badge-status-revisi';
                $label      = 'Perlu Revisi';
            }

            $approvedAt = $perumahan->approved_at
                ? ($perumahan->approved_at instanceof \Illuminate\Support\Carbon
                    ? $perumahan->approved_at
                    : \Carbon\Carbon::parse($perumahan->approved_at))
                : null;

            // ambil nama dari relasi approver (approved_by = user_id dinas)
            $approverName = optional($perumahan->approver)->name;
          @endphp

          <span class="badge-status {{ $badgeClass }}">
            Status: {{ $label }}
          </span>

          @if($approvedAt)
            <div style="font-size:11px;color:#6b7280;margin-top:4px;">
              Disetujui pada {{ $approvedAt->format('d M Y H:i') }}
              @if($approverName)
                oleh {{ $approverName }}
              @endif
            </div>
          @endif
        </div>

        {{-- TABEL INFO --}}
        <table class="info-table">
          <tr>
            <th>Nama Perumahan</th>
            <td>{{ $perumahan->nama }}</td>
          </tr>
          <tr>
            <th>Developer</th>
            <td>{{ optional($perumahan->developer)->name ?? '-' }}</td>
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

        {{-- CATATAN REVISI DARI DINAS --}}
        @if($perumahan->catatan_revisi)
          <div style="margin-top:18px;">
            <div class="section-title">Catatan Revisi dari Dinas</div>
            <div style="font-size:13px;line-height:1.6;color:#b91c1c;
                        background:#fef2f2;border-radius:10px;
                        padding:10px 12px;border:1px solid #fecaca;
                        white-space:pre-line;">
              {{ $perumahan->catatan_revisi }}
            </div>
          </div>
        @endif

        {{-- FORM TANDAI PERLU REVISI (hanya jika belum disetujui) --}}
        @if($perumahan->status !== 'disetujui')
          <div style="margin-top:18px;">
            <div class="section-title">Tandai Perlu Revisi</div>
            <p class="section-sub">
              Isi catatan revisi yang akan dikirim ke developer (opsional, tapi disarankan).
            </p>

            <form method="POST"
                  action="{{ route('dinas.perumahan.reject', $perumahan->id) }}"
                  onsubmit="return confirm('Tandai perumahan ini perlu revisi?');">
              @csrf
              <textarea
                name="catatan_revisi"
                rows="3"
                style="width:100%;font-size:13px;border-radius:8px;
                       border:1px solid #d1d5db;padding:8px 10px;resize:vertical;">{{ old('catatan_revisi', $perumahan->catatan_revisi) }}</textarea>

              <button type="submit"
                      class="btn-plain"
                      style="margin-top:8px;border-color:#b91c1c;color:#b91c1c;">
                Tandai Perlu Revisi
              </button>
            </form>
          </div>
        @endif

      </div>
    </div>
  </section>

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
@endsection