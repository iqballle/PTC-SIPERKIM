<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Perumahan — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <!-- Styling tambahan khusus halaman ini -->
  <style>
    /* Grid utama 2 kolom, auto jadi 1 kolom di layar kecil */
    .filters-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: 24px;
      row-gap: 16px;
    }
    @media (max-width: 768px) {
      .filters-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Group label + input biar sejajar rapi */
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    /* Field yang harus full width (textarea, dokumen, dll.) */
    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-hint {
      font-size: 12px;
      color: #6b7280; /* abu-abu */
    }

    /* ===== Modal konfirmasi ===== */
    .dev-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.45);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    .dev-hidden {
      display: none !important;
    }
    .dev-modal {
      background: #ffffff;
      border-radius: 14px;
      padding: 20px 24px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 20px 60px rgba(15,23,42,0.35);
      text-align: center;
    }
    .dev-modal-title {
      font-size: 15px;
      font-weight: 600;
      line-height: 1.4;
      margin-bottom: 18px;
    }
    .dev-modal-actions {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 4px;
    }
    .dev-modal-actions .btn-plain {
      min-width: 110px;
    }
    .dev-modal-actions .btn-primary {
      min-width: 140px;
    }
  </style>
</head>
<body class="font-[Inter] antialiased">

  <div id="wrapper" class="flex">
    <!-- SIDEBAR -->
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


    <!-- KONTEN -->
    <main id="content" class="content">
      <button id="sidebar-toggle" class="sidebar-toggle" type="button">☰</button>

      <h1>Tambah Perumahan</h1>

      @if ($errors->any())
        <div class="card" style="border-color:#f5c2c7;background:#fff5f6;padding:12px 16px;margin-bottom:16px;">
          @foreach ($errors->all() as $e)
            <div>• {{ $e }}</div>
          @endforeach
        </div>
      @endif

      <form class="card" style="padding:20px 24px;" method="POST"
            action="{{ route('developer.perumahan.store') }}"
            enctype="multipart/form-data"
            id="form-create-perumahan">
        @csrf

        <div class="filters-grid">
          {{-- ================== DATA UTAMA ================== --}}

          <!-- Nama Perumahan -->
          <div class="form-group">
            <label>Nama Perumahan <span class="text-red-600">*</span></label>
            <input class="input" type="text" name="nama" value="{{ old('nama') }}" required>
          </div>

          <!-- Nama Perusahaan -->
          <!-- Nama Perusahaan -->
          <div class="form-group">
            <label>Nama Perusahaan (opsional)</label>
            <input
              class="input"
              type="text"
              name="nama_perusahaan"
              value="{{ old('nama_perusahaan', $lastCompanyName ?? '') }}"
              placeholder="Otomatis terisi dari perumahan terakhir (jika ada)"
            >
          </div>

          <!-- Alamat / Lokasi -->
          <div class="form-group">
            <label>Alamat / Lokasi <span class="text-red-600">*</span></label>
            <input class="input" type="text" name="lokasi"
                   placeholder="Contoh: Jl. Bau Massepe No.45, Parepare"
                   value="{{ old('lokasi') }}" required>
          </div>

          <div class="form-group">
            <label>Status Unit <span class="text-red-600">*</span></label>
            <select name="status_unit" class="input" required>
              <option value="">-- Pilih Status Unit --</option>
          
              <option value="Tersedia" {{ old('status_unit', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>
                Unit tersedia
              </option>
          
              <option value="Tidak Tersedia" {{ old('status_unit') == 'Tidak Tersedia' ? 'selected' : '' }}>
                Unit tidak tersedia
              </option>
            </select>
            <small class="form-hint">
              Atur apakah unit di perumahan ini masih tersedia atau sudah habis.
            </small>
          
            @error('status_unit')
              <small class="text-red-600">{{ $message }}</small>
            @enderror
          </div>
          

          <!-- Link HTML Google Maps (embed) -->
          <div class="form-group">
            <label>Link HTML Google Maps (opsional)</label>
            <input class="input" type="text" name="lokasi_google_map"
                   placeholder="https://www.google.com/maps/embed?pb=..."
                   value="{{ old('lokasi_google_map') }}">
            <small class="form-hint">
              Buka Google Maps → cari lokasi → klik <strong>Bagikan</strong> → pilih
              <strong>Sematkan peta</strong> → salin bagian <code>src</code> di dalam
              iframe (diawali <code>https://www.google.com/maps/embed?pb=...</code>) lalu tempel di sini.
            </small>
          </div>

          <!-- Harga Per Unit -->
          <div class="form-group">
            <label>Harga Per Unit (opsional)</label>
            <input class="input" type="number" min="0" name="harga"
                   placeholder="Contoh: 250000000"
                   value="{{ old('harga') }}">
            <small class="form-hint">Masukkan harga tanpa titik/koma (contoh: 250000000)</small>
          </div>

          <!-- Tipe Rumah -->
          <div class="form-group">
            <label>Tipe Rumah (opsional)</label>
            <input class="input" type="text" name="tipe" placeholder="Contoh: Tipe 36/72"
                   value="{{ old('tipe') }}">
          </div>

          <div class="form-group">
            <label>Tahun Pembangunan (opsional)</label>
            <input class="input" type="number" name="tahun_pembangunan"
                  placeholder="Contoh: 2017"
                  value="{{ old('tahun_pembangunan') }}">
          </div>

          <!-- Jumlah Unit -->
          <div class="form-group">
            <label>Jumlah Unit (opsional)</label>
            <input class="input" type="number" min="0" name="jumlah_unit"
                  placeholder="Contoh: 60"
                  value="{{ old('jumlah_unit') }}">
          </div>

          <!-- Luas Tanah -->
          <div class="form-group">
            <label>Luas Tanah (m²) (opsional)</label>
            <input class="input" type="number" min="0" name="luas_tanah"
                  placeholder="Contoh: 96"
                  value="{{ old('luas_tanah') }}">
          </div>

          <!-- Luas Bangunan -->
          <div class="form-group">
            <label>Luas Bangunan (m²) (opsional)</label>
            <input class="input" type="number" min="0" name="luas_bangunan"
                  placeholder="Contoh: 36"
                  value="{{ old('luas_bangunan') }}">
          </div>

          <!-- Fasilitas -->
          <div class="form-group full">
            <label>Fasilitas (opsional)</label>
            <textarea class="input" name="fasilitas" rows="3"
                      placeholder="Contoh: jalan paving, taman, mushola, keamanan 24 jam">{{ old('fasilitas') }}</textarea>
          </div>

          <!-- Deskripsi -->
          <div class="form-group full">
            <label>Deskripsi (opsional)</label>
            <textarea class="input" name="deskripsi" rows="4"
                      placeholder="Deskripsikan lokasi, tipe unit, fasilitas sekitar, dsb.">{{ old('deskripsi') }}</textarea>
          </div>

          {{-- SPESIFIKASI: 2 kolom × 3 baris --}}
          <div class="form-group full">
            <label>Spesifikasi (opsional)</label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              {{-- Kolom kiri --}}
              <div class="space-y-2">
                <input class="input" type="text" name="spesifikasi_kiri_1"
                      placeholder="Contoh: Pondasi batu gunung"
                      value="{{ old('spesifikasi_kiri_1') }}">
                <input class="input" type="text" name="spesifikasi_kiri_2"
                      placeholder="Contoh: Kusen kayu Kelas II"
                      value="{{ old('spesifikasi_kiri_2') }}">
                <input class="input" type="text" name="spesifikasi_kiri_3"
                      placeholder="Contoh: Plafon gypsum"
                      value="{{ old('spesifikasi_kiri_3') }}">
              </div>

              {{-- Kolom kanan --}}
              <div class="space-y-2">
                <input class="input" type="text" name="spesifikasi_kanan_1"
                      placeholder="Contoh: Atap baja ringan"
                      value="{{ old('spesifikasi_kanan_1') }}">
                <input class="input" type="text" name="spesifikasi_kanan_2"
                      placeholder="Contoh: Closet jongkok"
                      value="{{ old('spesifikasi_kanan_2') }}">
                <input class="input" type="text" name="spesifikasi_kanan_3"
                      placeholder="Contoh: Lantai keramik 60×60"
                      value="{{ old('spesifikasi_kanan_3') }}">
              </div>
            </div>

            <small class="form-hint">
              Isi maksimal 6 poin (3 di kiri, 3 di kanan). Di halaman detail akan ditampilkan dalam tabel dua kolom.
            </small>
          </div>

          {{-- ================== KONTAK & FILE ================== --}}

          <!-- No. Telepon -->
          <div class="form-group">
            <label>No. Telepon (opsional)</label>
            <input class="input" type="text" name="telepon" value="{{ old('telepon') }}">
          </div>

          <!-- Cover -->
          <div class="form-group">
            <label>Cover (1 gambar)</label>
            <input class="input" type="file" name="cover" accept="image/*">
            <small class="form-hint">Maks 5MB • Format: JPG/PNG/WebP</small>
          </div>

          <!-- Foto Perumahan -->
          <div class="form-group">
            <label>Foto Perumahan (maks 5)</label>
            <input class="input" type="file" name="gallery[]" accept="image/*" multiple>
            <small class="form-hint">Upload 1–5 foto • maks 5MB per foto</small>
          </div>

          <!-- Foto Tabel Angsuran -->
          <div class="form-group">
            <label>Foto Tabel Perkiraan & Angsuran (opsional)</label>
            <input class="input" type="file" name="tabel_angsuran" accept="image/*">
            <small class="form-hint">
              Gambar tabel angsuran (misal simulasi KPR) • maks 5MB • JPG/PNG/WebP.
            </small>
          </div>

          <!-- Foto Denah Rumah -->
          <div class="form-group">
            <label>Foto Denah Rumah (opsional)</label>
            <input class="input" type="file" name="denah_rumah" accept="image/*">
            <small class="form-hint">
              Denah rumah tampak atas • maks 5MB • JPG/PNG/WebP.
            </small>
          </div>

          <!-- Dokumen -->
          <div class="form-group full">
            <label>Foto Dokumen (maks 3) — IMB, sertifikat, dsb.</label>
            <input class="input" type="file" name="dokumen_foto[]" accept="image/*" multiple>
            <small class="form-hint">
              Upload hingga 3 foto dokumen legalitas (IMB, sertifikat, izin, dll) • maks 5MB per foto.
            </small>
          </div>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;">
          {{-- tombol pakai konfirmasi dulu --}}
          <button class="btn-primary" type="button" id="btn-create-submit">
            Simpan & Ajukan Verifikasi
          </button>
          <a class="btn-plain" href="{{ route('developer.perumahan.index') }}">Batal</a>
        </div>
      </form>
    </main>
  </div>

  {{-- MODAL KONFIRMASI CREATE --}}
  <div id="confirmCreateModal" class="dev-modal-backdrop dev-hidden">
    <div class="dev-modal">
      <p class="dev-modal-title">
        Apakah Anda yakin ingin mengirim data perumahan ini ke Dinas untuk diverifikasi?
      </p>
      <div class="dev-modal-actions">
        <button type="button" class="btn-plain dev-modal-cancel">Batal</button>
        <button type="button" class="btn-primary dev-modal-ok">Kirim Sekarang</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form   = document.getElementById('form-create-perumahan');
      const btn    = document.getElementById('btn-create-submit');
      const modal  = document.getElementById('confirmCreateModal');

      if (!form || !btn || !modal) return;

      const btnCancel = modal.querySelector('.dev-modal-cancel');
      const btnOk     = modal.querySelector('.dev-modal-ok');

      btn.addEventListener('click', function () {
        modal.classList.remove('dev-hidden');
      });

      btnCancel.addEventListener('click', function () {
        modal.classList.add('dev-hidden');
      });

      btnOk.addEventListener('click', function () {
        modal.classList.add('dev-hidden');
        form.submit(); // baru kirim ke server
      });

      // klik area gelap untuk menutup
      modal.addEventListener('click', function (e) {
        if (e.target === modal) {
          modal.classList.add('dev-hidden');
        }
      });
    });
  </script>
</body>
</html>
