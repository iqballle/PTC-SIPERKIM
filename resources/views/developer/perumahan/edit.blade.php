<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Perumahan — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <style>
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
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .form-group.full {
      grid-column: 1 / -1;
    }
    .form-hint {
      font-size: 12px;
      color: #6b7280;
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
    <button id="sidebar-toggle" class="sidebar-toggle" type="button">☰</button>

    <h1>Edit Perumahan</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:4px;margin-bottom:16px;">
      Setiap perubahan akan dikirim ulang untuk <strong>verifikasi dinas</strong> sebelum tampil di dashboard masyarakat.
    </p>

    @if ($errors->any())
      <div class="card" style="border-color:#f5c2c7;background:#fff5f6;padding:12px 16px;margin-bottom:16px;">
        @foreach ($errors->all() as $e)
          <div>• {{ $e }}</div>
        @endforeach
      </div>
    @endif

    {{-- PANEL STATUS & CATATAN REVISI --}}
    @php
      $rawStatus  = $perumahan->status ?? 'pending';
      $hasRevisi  = !empty($perumahan->catatan_revisi ?? null);
    @endphp

    @if($rawStatus === 'disetujui')
      <div class="card" style="margin-bottom:16px;border-color:#bbf7d0;background:#ecfdf5;font-size:13px;">
        <strong>Perumahan sudah DISETUJUI dinas.</strong><br>
        Jika Anda mengubah data dan menyimpan, status akan kembali <em>pending</em> dan menunggu verifikasi ulang.
      </div>
    @elseif($rawStatus === 'pending' && $hasRevisi)
      <div class="card" style="margin-bottom:16px;border-color:#fecaca;background:#fef2f2;font-size:13px;color:#7f1d1d;">
        <strong>Perlu Revisi dari Dinas</strong><br>
        {!! nl2br(e($perumahan->catatan_revisi)) !!}
        <br><br>
        <span style="font-size:12px;color:#9f1239;">
          Setelah Anda memperbaiki data dan menekan simpan, data akan dikirim ulang
          ke dinas dengan status <em>pending</em>.
        </span>
      </div>
    @elseif($rawStatus === 'pending')
      <div class="card" style="margin-bottom:16px;border-color:#facc15;background:#fefce8;font-size:13px;">
        <strong>Status: Pending</strong><br>
        Data Anda sedang menunggu verifikasi dari dinas.
      </div>
    @endif

    @php
        // PRE-FILL SPESIFIKASI dari kolom teks $perumahan->spesifikasi
        $spesLines = [];
        if ($perumahan->spesifikasi) {
            $parts = preg_split('/\r\n|\r|\n|,/', $perumahan->spesifikasi);
            $spesLines = array_values(array_filter(array_map('trim', $parts)));
        }

        // Bagi jadi 6 slot (3 kiri, 3 kanan)
        $spes_kiri_1_db  = $spesLines[0] ?? null;
        $spes_kiri_2_db  = $spesLines[1] ?? null;
        $spes_kiri_3_db  = $spesLines[2] ?? null;
        $spes_kanan_1_db = $spesLines[3] ?? null;
        $spes_kanan_2_db = $spesLines[4] ?? null;
        $spes_kanan_3_db = $spesLines[5] ?? null;
    @endphp

    <form class="card" style="padding:20px 24px;"
          method="POST"
          action="{{ route('developer.perumahan.update', $perumahan->id) }}"
          enctype="multipart/form-data"
          id="form-edit-perumahan">
      @csrf
      @method('PUT')

      <div class="filters-grid">
        {{-- ================== DATA UTAMA ================== --}}

        <div class="form-group">
          <label>Nama Perumahan <span class="text-red-600">*</span></label>
          <input class="input" type="text" name="nama"
                 value="{{ old('nama', $perumahan->nama) }}" required>
        </div>

        <div class="form-group">
          <label>Nama Perusahaan (opsional)</label>
          <input class="input" type="text" name="nama_perusahaan"
                 value="{{ old('nama_perusahaan', $perumahan->nama_perusahaan) }}">
        </div>

        <div class="form-group">
          <label>Alamat / Lokasi <span class="text-red-600">*</span></label>
          <input class="input" type="text" name="lokasi"
                 placeholder="Contoh: Jl. Bau Massepe No.45, Parepare"
                 value="{{ old('lokasi', $perumahan->lokasi) }}" required>
        </div>

        <div class="form-group">
          <label>Link HTML Google Maps (opsional)</label>
          <input class="input" type="text" name="lokasi_google_map"
                 placeholder="https://www.google.com/maps/embed?pb=..."
                 value="{{ old('lokasi_google_map', $perumahan->lokasi_google_map) }}">
          <small class="form-hint">
            Buka Google Maps → cari lokasi → Bagikan → Sematkan peta → salin bagian
            <code>src</code> (https://www.google.com/maps/embed?pb=...) lalu tempel di sini.
          </small>
        </div>

        <div class="form-group">
          <label>Harga Per Unit (opsional)</label>
          <input class="input" type="number" min="0" name="harga"
                 placeholder="Contoh: 250000000"
                 value="{{ old('harga', $perumahan->harga) }}">
          <small class="form-hint">Masukkan harga tanpa titik/koma (contoh: 250000000)</small>
        </div>

        <div class="form-group">
          <label>Tipe Rumah (opsional)</label>
          <input class="input" type="text" name="tipe"
                 placeholder="Contoh: Tipe 36/72"
                 value="{{ old('tipe', $perumahan->tipe) }}">
        </div>

        {{-- DATA TEKNIS TAMBAHAN --}}
        <div class="form-group">
          <label>Tahun Pembangunan (opsional)</label>
          <input class="input" type="text" name="tahun_pembangunan"
                 placeholder="Contoh: 2024"
                 value="{{ old('tahun_pembangunan', $perumahan->tahun_pembangunan) }}">
        </div>

        <div class="form-group">
          <label>Luas Tanah (m²)</label>
          <input class="input" type="number" min="0" name="luas_tanah"
                 value="{{ old('luas_tanah', $perumahan->luas_tanah) }}">
        </div>

        <div class="form-group">
          <label>Luas Bangunan (m²)</label>
          <input class="input" type="number" min="0" name="luas_bangunan"
                 value="{{ old('luas_bangunan', $perumahan->luas_bangunan) }}">
        </div>

        <div class="form-group">
          <label>Jumlah Unit</label>
          <input class="input" type="number" min="0" name="jumlah_unit"
                 value="{{ old('jumlah_unit', $perumahan->jumlah_unit) }}">
        </div>

        {{-- STATUS UNIT (sinkron dengan controller: Tersedia / Tidak Tersedia) --}}
        <div class="form-group">
          <label>Status Unit</label>
          @php
            $su = old('status_unit', $perumahan->status_unit ?? 'Tersedia');
          @endphp
          <select class="input" name="status_unit">
            <option value="Tersedia"      {{ $su=='Tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="Tidak Tersedia" {{ $su=='Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
          </select>
          <small class="form-hint">
            Atur apakah unit di perumahan ini masih tersedia atau sudah habis.
          </small>
        </div>

        <div class="form-group full">
          <label>Fasilitas (opsional)</label>
          <textarea class="input" name="fasilitas" rows="3"
                    placeholder="Contoh: jalan paving, taman, mushola, keamanan 24 jam">{{ old('fasilitas', $perumahan->fasilitas) }}</textarea>
        </div>

        <div class="form-group full">
          <label>Deskripsi (opsional)</label>
          <textarea class="input" name="deskripsi" rows="4"
                    placeholder="Deskripsikan lokasi, tipe unit, fasilitas sekitar, dsb.">{{ old('deskripsi', $perumahan->deskripsi) }}</textarea>
        </div>

        {{-- SPESIFIKASI: 2 kolom × 3 baris (prefill dari DB) --}}
        <div class="form-group full">
          <label>Spesifikasi (opsional)</label>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            {{-- Kolom kiri --}}
            <div class="space-y-2">
              <input class="input" type="text" name="spesifikasi_kiri_1"
                     placeholder="Contoh: Pondasi batu gunung"
                     value="{{ old('spesifikasi_kiri_1', $spes_kiri_1_db) }}">
              <input class="input" type="text" name="spesifikasi_kiri_2"
                     placeholder="Contoh: Kusen kayu Kelas II"
                     value="{{ old('spesifikasi_kiri_2', $spes_kiri_2_db) }}">
              <input class="input" type="text" name="spesifikasi_kiri_3"
                     placeholder="Contoh: Plafon gypsum"
                     value="{{ old('spesifikasi_kiri_3', $spes_kiri_3_db) }}">
            </div>

            {{-- Kolom kanan --}}
            <div class="space-y-2">
              <input class="input" type="text" name="spesifikasi_kanan_1"
                     placeholder="Contoh: Atap baja ringan"
                     value="{{ old('spesifikasi_kanan_1', $spes_kanan_1_db) }}">
              <input class="input" type="text" name="spesifikasi_kanan_2"
                     placeholder="Contoh: Closet jongkok"
                     value="{{ old('spesifikasi_kanan_2', $spes_kanan_2_db) }}">
              <input class="input" type="text" name="spesifikasi_kanan_3"
                     placeholder="Contoh: Lantai keramik 60×60"
                     value="{{ old('spesifikasi_kanan_3', $spes_kanan_3_db) }}">
            </div>
          </div>

          <small class="form-hint">
            Isi maksimal 6 poin (3 di kiri, 3 di kanan). Di halaman detail akan ditampilkan dalam tabel dua kolom.
          </small>
        </div>

        {{-- KONTAK & FILE --}}
        <div class="form-group">
          <label>No. Telepon (opsional)</label>
          <input class="input" type="text" name="telepon"
                 value="{{ old('telepon', $perumahan->telepon) }}">
        </div>

        <div class="form-group">
          <label>Cover (1 gambar)</label>
          <input class="input" type="file" name="cover" accept="image/*">
          <small class="form-hint">
            Maks 5MB • Format: JPG/PNG/WebP.<br>
            @if($perumahan->cover_url)
              Cover saat ini: <a href="{{ $perumahan->cover_url }}" target="_blank">lihat</a>
            @else
              Belum ada cover.
            @endif
          </small>
        </div>

        <div class="form-group">
          <label>Foto Perumahan (maks 5)</label>
          <input class="input" type="file" name="gallery[]" accept="image/*" multiple>
          <small class="form-hint">
            Upload 1–5 foto • maks 5MB per foto.<br>
            Jika diisi, foto lama akan diganti.
          </small>
        </div>

        <div class="form-group">
          <label>Foto Tabel Perkiraan & Angsuran (opsional)</label>
          <input class="input" type="file" name="tabel_angsuran" accept="image/*">
          <small class="form-hint">
            @if($perumahan->tabel_angsuran_url ?? false)
              Tabel saat ini: <a href="{{ $perumahan->tabel_angsuran_url }}" target="_blank">lihat</a>
            @else
              Belum ada tabel angsuran.
            @endif
          </small>
        </div>

        <div class="form-group">
          <label>Foto Denah Rumah (opsional)</label>
          <input class="input" type="file" name="denah_rumah" accept="image/*">
          <small class="form-hint">
            @if($perumahan->denah_url ?? false)
              Denah saat ini: <a href="{{ $perumahan->denah_url }}" target="_blank">lihat</a>
            @else
              Belum ada denah rumah.
            @endif
          </small>
        </div>

        <div class="form-group full">
          <label>Foto Dokumen (maks 3) — IMB, sertifikat, dsb.</label>
          <input class="input" type="file" name="dokumen_foto[]" accept="image/*" multiple>
          <small class="form-hint">
            Jika diisi, dokumen lama akan diganti.<br>
            @if($perumahan->dokumen_foto_urls && count($perumahan->dokumen_foto_urls))
              Dokumen saat ini:
              @foreach($perumahan->dokumen_foto_urls as $url)
                <a href="{{ $url }}" target="_blank">[lihat]</a>
              @endforeach
            @else
              Belum ada dokumen yang diunggah.
            @endif
          </small>
        </div>
      </div>

      <div style="margin-top:16px;display:flex;gap:10px;">
        <button class="btn-primary" type="button" id="btn-edit-submit">
          Simpan Perubahan
        </button>
        <a class="btn-plain" href="{{ route('developer.perumahan.index') }}">Batal</a>
      </div>
    </form>
  </main>
</div>

{{-- MODAL KONFIRMASI EDIT --}}
<div id="confirmEditModal" class="dev-modal-backdrop dev-hidden">
  <div class="dev-modal">
    <p class="dev-modal-title">
      Apakah Anda yakin ingin menyimpan perubahan dan mengirim ulang data ini ke Dinas untuk diverifikasi?
    </p>
    <div class="dev-modal-actions">
      <button type="button" class="btn-plain dev-modal-cancel">Batal</button>
      <button type="button" class="btn-primary dev-modal-ok">Simpan & Kirim</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form   = document.getElementById('form-edit-perumahan');
    const btn    = document.getElementById('btn-edit-submit');
    const modal  = document.getElementById('confirmEditModal');

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
