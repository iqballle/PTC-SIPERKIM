<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Permohonan Nota Dinas Pembangunan Perumahan — SIPERKIM</title>

  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dev-housing.css') }}">
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    .filters-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      column-gap: 24px;
      row-gap: 16px;
    }
    @media (max-width: 900px) {
      .filters-grid {
        grid-template-columns: 1fr;
      }
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 4px;
      font-size: 13px;
    }
    .form-group.full {
      grid-column: 1 / -1;
    }
    .form-hint {
      font-size: 12px;
      color: #6b7280;
    }
    .subsection-title {
      font-size: 14px;
      font-weight: 700;
      margin: 16px 0 4px;
    }
    .subsection-sub {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: 8px;
    }

    /* Modal konfirmasi */
    .confirm-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.45);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    .confirm-modal {
      background: #ffffff;
      border-radius: 16px;
      padding: 20px 22px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    .confirm-modal h3 {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .confirm-modal p {
      font-size: 13px;
      color: #374151;
      margin-bottom: 18px;
      line-height: 1.5;
    }
    .confirm-actions {
      display: flex;
      justify-content: center;
      gap: 12px;
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
        <h1>Permohonan Nota Dinas Pembangunan Perumahan</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:4px;">
          Lengkapi identitas dan unggah dokumen persyaratan sesuai daftar yang diminta Dinas.
        </p>
      </div>
      <div class="topbar-right">
        <a href="{{ route('developer.permohonan.index') }}" class="btn-plain">← Kembali</a>
      </div>
    </div>

    @if ($errors->any())
      <div class="card" style="border-color:#f5c2c7;background:#fff5f6;padding:12px 16px;margin-bottom:16px;">
        @foreach ($errors->all() as $e)
          <div>• {{ $e }}</div>
        @endforeach
      </div>
    @endif

    <form id="formNotaDinas" class="card" style="padding:20px 24px;"
          method="POST"
          action="{{ route('developer.permohonan.nota.store') }}"
          enctype="multipart/form-data">
      @csrf

      {{-- IDENTITAS PENGEMBANG & PERUMAHAN --}}
      <div class="subsection-title">A. Identitas Pengembang & Perumahan</div>
      <p class="subsection-sub">
        Pastikan data sesuai dengan dokumen resmi yang akan diunggah.
      </p>

      <div class="filters-grid">
        <div class="form-group full">
          <label>Pilih Perumahan <span class="text-red-600">*</span></label>
          <select name="perumahan_id" class="input" required>
            <option value="">-- Pilih Perumahan --</option>
            @foreach($perumahans as $p)
              <option value="{{ $p->id }}" @selected(old('perumahan_id') == $p->id)>
                {{ $p->nama }} — {{ $p->lokasi }}
              </option>
            @endforeach
          </select>
          <small class="form-hint">
            Hanya perumahan yang terdaftar di akun Anda yang bisa diajukan permohonannya.
          </small>
        </div>

        <div class="form-group">
          <label>Nama Pengembang <span class="text-red-600">*</span></label>
          <input type="text" name="nama_pengembang" class="input"
                 value="{{ old('nama_pengembang') }}" required>
        </div>

        <div class="form-group">
          <label>Nama Perumahan <span class="text-red-600">*</span></label>
          <input type="text" name="nama_perumahan" class="input"
                 value="{{ old('nama_perumahan') }}" required>
          <small class="form-hint">Boleh disamakan dengan nama perumahan yang dipilih.</small>
        </div>

        <div class="form-group">
          <label>Telepon / HP</label>
          <input type="text" name="telepon" class="input"
                 value="{{ old('telepon') }}">
        </div>

        <div class="form-group">
          <label>Alamat Perumahan <span class="text-red-600">*</span></label>
          <input type="text" name="alamat_perumahan" class="input"
                 value="{{ old('alamat_perumahan') }}" required>
        </div>

        <div class="form-group">
          <label>Kelurahan <span class="text-red-600">*</span></label>
          <input type="text" name="kelurahan" class="input"
                 value="{{ old('kelurahan') }}" required>
        </div>

        <div class="form-group">
          <label>Kecamatan <span class="text-red-600">*</span></label>
          <input type="text" name="kecamatan" class="input"
                 value="{{ old('kecamatan') }}" required>
        </div>

        <div class="form-group full">
          <label>Keterangan Tambahan (opsional)</label>
          <textarea name="keterangan_tambahan" class="input" rows="3"
                    placeholder="Catatan lain yang perlu diketahui Dinas (misal tahap pembangunan, dll).">{{ old('keterangan_tambahan') }}</textarea>
        </div>
      </div>

      {{-- DOKUMEN PERSYARATAN (sesuai list di foto) --}}
      <div class="subsection-title" style="margin-top:22px;">B. Dokumen Persyaratan</div>
      <p class="subsection-sub">
        Unggah scan / PDF sesuai uraian. Format file: <strong>PDF / JPG / JPEG / PNG</strong>, maksimal 5 MB per berkas.
      </p>

      <div class="filters-grid">
        {{-- 1 --}}
        <div class="form-group full">
          <label>1. Surat Permohonan Nota Dinas / Pengesahan Site Plan (Rencana Tapak)</label>
          <input type="file" name="surat_permohonan" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        {{-- 2 Profil Perusahaan & turunannya --}}
        <div class="form-group full">
          <label>2. Profil Perusahaan</label>
          <input type="file" name="profil_perusahaan" class="input" accept=".pdf,.jpg,.jpeg,.png">
          <small class="form-hint">Bisa berupa company profile atau dokumen ringkasan perusahaan.</small>
        </div>

        <div class="form-group">
          <label> - Fotocopy KTP Direktur/Direktris</label>
          <input type="file" name="ktp_direktur" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Fotocopy NPWP Perusahaan</label>
          <input type="file" name="npwp_perusahaan" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label> - Fotocopy Akte Pendirian Perusahaan</label>
          <input type="file" name="akte_pendirian" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        {{-- 3–8 --}}
        <div class="form-group full">
          <label>3. Surat Pernyataan Kesiapan Penyerahan PSU Perumahan</label>
          <input type="file" name="surat_kesiapan_psu" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label>4. Surat Keterangan Lahan Tidak Dalam Sengketa dari Kelurahan</label>
          <input type="file" name="surat_tidak_sengketa" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label>5. Persetujuan Kesesuaian Kegiatan Pemanfaatan Ruang (PKKPR)</label>
          <input type="file" name="pkkpr" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label>6. Nomor Induk Berusaha (NIB) dan KBLI</label>
          <input type="file" name="nib_kbli" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label>7. Peil Banjir (Opsional)</label>
          <input type="file" name="peil_banjir" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label>8. Fotocopy Alas Hak Kepemilikan Lahan</label>
          <input type="file" name="alas_hak" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        {{-- 9. Dokumen Site Plan / Rencana Tapak --}}
        <div class="form-group full">
          <label>9. Perumahan Tahap Pengembangan (Fotocopy BAST)</label>
          <input type="file" name="bast_tahap_pengembangan" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label>10. Site Plan (Rencana Tapak) / Kertas A3</label>
          <input type="file" name="siteplan_a3" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Peta Lokasi</label>
          <input type="file" name="peta_lokasi" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Site Plan</label>
          <input type="file" name="site_plan" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label> - Site Plan Penempatan Kontur Tanah</label>
          <input type="file" name="kontur_tanah" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Rencana Jalan dan Detailnya</label>
          <input type="file" name="rencana_jalan" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Rencana Drainase dan Detailnya</label>
          <input type="file" name="rencana_drainase" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Rencana RTH dan Detailnya</label>
          <input type="file" name="rencana_rth" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Rencana Jaringan Air Bersih dan Detailnya</label>
          <input type="file" name="rencana_air_bersih" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label> - Rencana Sanitasi dan Detailnya</label>
          <input type="file" name="rencana_sanitasi" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group full">
          <label> - Rencana Fasum Fasos lainnya yang direncanakan dan detailnya</label>
          <input type="file" name="rencana_fasum_fasos" class="input" accept=".pdf,.jpg,.jpeg,.png">
        </div>
      </div>

      {{-- PERINGATAN (sesuai catatan di bawah form asli) --}}
      <div class="subsection-title" style="margin-top:22px;">Catatan / Peringatan</div>
      <ul class="form-hint" style="padding-left:16px;line-height:1.6;">
        <li>• Fasum/Fasos minimal sebesar 20% dari luas lahan yang dimohonkan.</li>
        <li>• RTH minimal 20% dari luas lahan yang dimohonkan.</li>
        <li>• Penempatan RTH sedapat mungkin satu hamparan.</li>
        <li>• RTH tidak diperkenankan hanya di sisa-sisa sudut tanah / sudut rumah.</li>
        <li>• Luasan minimal satu penempatan RTH seluas 24 m².</li>
      </ul>

      {{-- TOMBOL AKSI --}}
      <div style="margin-top:18px;display:flex;gap:10px;justify-content:flex-end;">
        <a href="{{ route('developer.permohonan.index') }}" class="btn-plain">Batal</a>
        <button type="submit"
                class="btn-primary"
                onclick="return confirm('Apakah Anda yakin ingin mengirim permohonan Nota Dinas beserta dokumennya ke Dinas?');">
          Kirim ke Dinas
        </button>
      </div>      
    </form>
  </main>
</div>

{{-- MODAL KONFIRMASI --}}
<div id="confirmModalWrapper" class="confirm-modal-backdrop" style="display:none;">
  <div class="confirm-modal">
    <h3>Apakah Anda yakin?</h3>
    <p>
      Apakah Anda yakin ingin mengirim permohonan <strong>Nota Dinas Pembangunan Perumahan</strong>
      beserta dokumen tersebut ke Dinas untuk diverifikasi?
    </p>
    <div class="confirm-actions">
      <button type="button" id="btnCancelConfirm" class="btn-plain">Batal</button>
      <button type="button" id="btnOkConfirm" class="btn-primary">Kirim Sekarang</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form     = document.getElementById('formNotaDinas');
    const btn      = document.getElementById('btnSubmitNota');
    const modal    = document.getElementById('confirmModalWrapper');
    const btnOk    = document.getElementById('btnOkConfirm');
    const btnCancel= document.getElementById('btnCancelConfirm');

    if (!form || !btn || !modal || !btnOk || !btnCancel) return;

    btn.addEventListener('click', function () {
      modal.style.display = 'flex';
    });

    btnCancel.addEventListener('click', function () {
      modal.style.display = 'none';
    });

    btnOk.addEventListener('click', function () {
      // submit form setelah konfirmasi
      form.submit();
    });

    // tutup modal jika klik area gelap
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
</script>


</body>
</html>
