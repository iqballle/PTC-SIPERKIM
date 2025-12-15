<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengaturan Akun — SIPERKIM</title>

  {{-- CSS utama dashboard developer --}}
  <link rel="stylesheet" href="{{ asset('css/dev-dashboard.css') }}">

  {{-- JS toggle sidebar --}}
  @vite('resources/js/dashboard.js')

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ====== STYLE KHUSUS HALAMAN PENGATURAN DEVELOPER ====== */
    .settings-wrapper {
      max-width: 920px;
      margin: 0 auto;
    }

    .settings-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
    }

    .settings-header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: 800;
    }

    .settings-user {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .settings-user-avatar {
      width: 60px;
      height: 60px;
      border-radius: 9999px;
      object-fit: cover;
      background: #e5e7eb;
      cursor: pointer;
    }

    .settings-user-name {
      font-weight: 700;
      font-size: 15px;
    }

    .settings-user-role {
      font-size: 13px;
      color: #6b7280;
      margin-top: 2px;
    }

    .settings-card {
      background:#ffffff;
      border-radius:18px;
      border:1px solid #e5e7eb;
      padding:28px 32px 26px;
      box-shadow:0 10px 30px rgba(15,23,42,0.06);
    }

    .settings-card-title {
      font-size:20px;
      font-weight:700;
      margin:0 0 20px;
      text-align:left;
    }

    .settings-photo-box {
      display:flex;
      flex-direction:column;
      align-items:center;
      margin-bottom:20px;
      margin-top:6px;
      gap:6px;
    }

    .settings-photo-preview {
      width:88px;
      height:88px;
      border-radius:9999px;
      object-fit:cover;
      background:#e5e7eb;
      border:3px solid #e5e7eb;
      margin-bottom:6px;
      cursor:pointer;
    }

    .settings-photo-btn {
      border-radius:9999px;
      border:1px solid #d1d5db;
      background:#ffffff;
      padding:8px 22px;
      font-size:13px;
      font-weight:600;
      cursor:pointer;
    }

    .settings-photo-btn:disabled { cursor:not-allowed; opacity:.6; }
    .settings-photo-btn:hover:not(:disabled) { background:#f3f4f6; }

    .settings-form { max-width:520px; margin:0 auto; }

    .settings-field { margin-bottom:14px; }

    .settings-label {
      display:block;
      font-size:14px;
      font-weight:600;
      margin-bottom:6px;
    }

    .settings-input {
      width:100%;
      border-radius:8px;
      border:1px solid #d1d5db;
      padding:10px 12px;
      font-size:14px;
      outline:none;
      background:#ffffff;
      transition:border-color .15s, box-shadow .15s, background .15s;
    }

    .settings-input:focus {
      border-color:#5B7042;
      box-shadow:0 0 0 1px rgba(91,112,66,0.25);
    }

    .settings-input[disabled] {
      background:#f9fafb;
      color:#6b7280;
      cursor:not-allowed;
    }

    .settings-actions {
      display:flex;
      gap:14px;
      margin-top:18px;
    }

    .settings-actions button {
      flex:1;
      border:none;
      border-radius:8px;
      padding:10px 14px;
      font-size:14px;
      font-weight:600;
      cursor:pointer;
      transition:filter .15s, opacity .15s;
    }

    .settings-btn-edit { background:#5B7042; color:#ffffff; }
    .settings-btn-save { background:#4b8f3a; color:#ffffff; }

    .settings-actions button:hover:not(:disabled) { filter:brightness(1.05); }
    .settings-actions button:disabled { opacity:.55; cursor:not-allowed; }

    .settings-help {
      font-size:12px;
      color:#6b7280;
      margin-top:2px;
      text-align:center;
    }

    /* ===== MODAL PREVIEW AVATAR ===== */
    .avatar-modal-backdrop{
      position:fixed; inset:0;
      background:rgba(0,0,0,0.75);
      display:flex; align-items:center; justify-content:center;
      z-index:9999;
    }
    .avatar-modal-inner{ position:relative; max-width:90vw; max-height:90vh; }
    .avatar-modal-img{
      max-width:100%; max-height:90vh; border-radius:16px;
      box-shadow:0 20px 60px rgba(0,0,0,0.55); display:block;
    }
    .avatar-modal-close{
      position:absolute; top:-12px; right:-12px;
      width:34px; height:34px; border-radius:9999px;
      border:none; background:#111827; color:#f9fafb;
      font-size:18px; cursor:pointer;
      display:flex; align-items:center; justify-content:center;
    }
    .avatar-modal-hidden{ display:none !important; }

    /* ===== BADGE NOTIF DOT di sidebar ===== */
    .notif-dot{
      display:inline-flex; align-items:center; justify-content:center;
      min-width:18px; height:18px; padding:0 6px;
      margin-left:8px;
      border-radius:999px;
      background:#ef4444; color:#fff;
      font-size:11px; font-weight:800;
      line-height:1;
    }

    /* ===== LOGOUT CARD BUTTON ===== */
    .btn-logout{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:10px 14px;
      border-radius:10px;
      border:1px solid #b91c1c;
      background:#fff;
      color:#b91c1c;
      font-weight:700;
      font-size:13px;
      cursor:pointer;
    }
    .btn-logout:hover{ background:#fef2f2; }

    /* ===== MODAL LOGOUT ===== */
    .logout-modal { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; }
    .logout-modal.is-hidden { display:none; }
    .logout-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.55); }
    .logout-modal__panel {
      position:relative; width:min(520px, 92vw);
      background:#fff; border-radius:14px; padding:16px 16px 14px;
      box-shadow:0 20px 60px rgba(0,0,0,.25);
      border:1px solid #e5e7eb;
    }
    .logout-modal__title { margin:0; font-size:15px; font-weight:800; }
    .logout-modal__desc { margin:6px 0 10px; font-size:13px; color:#6b7280; }
    .logout-modal__input {
      width:100%; border:1px solid #d1d5db; border-radius:10px;
      padding:10px 12px; font-size:13px; outline:none;
    }
    .logout-modal__input:focus { border-color:#b91c1c; box-shadow:0 0 0 1px rgba(185, 28, 28, .25); }
    .logout-modal__actions { display:flex; justify-content:flex-end; gap:10px; margin-top:12px; }
    .logout-modal__error {
      margin-top:10px; font-size:12px; color:#b91c1c;
      background:#fef2f2; padding:8px 10px; border-radius:10px; border:1px solid #fecaca;
    }
    .logout-modal__error.is-hidden{ display:none; }

    @media (max-width: 768px){
      .settings-header { flex-direction:column; align-items:flex-start; gap:12px; }
      .settings-card { padding:20px 16px 18px; }
      .settings-form { max-width:100%; }
      .settings-actions { flex-direction:column; }
    }

    /* ===== BUTTONS KHUSUS MODAL LOGOUT ===== */
.logout-btn {
  border: 1px solid transparent;
  border-radius: 12px;
  padding: 10px 14px;
  font-size: 13px;
  font-weight: 800;
  cursor: pointer;
  transition: transform .12s ease, filter .12s ease, box-shadow .12s ease, background .12s ease, border-color .12s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-width: 120px;
  user-select: none;
}

.logout-btn:active {
  transform: translateY(1px);
}

.logout-btn:disabled {
  opacity: .55;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* Tombol BATAL */
.logout-btn-cancel {
  background: #ffffff;
  color: #111827;
  border-color: #e5e7eb;
  box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
}
.logout-btn-cancel:hover {
  background: #f9fafb;
  box-shadow: 0 14px 26px rgba(15, 23, 42, 0.10);
}

/* Tombol YA, LOGOUT */
.logout-btn-danger {
  background: linear-gradient(180deg, #ef4444 0%, #b91c1c 100%);
  color: #ffffff;
  border-color: rgba(0,0,0,0.05);
  box-shadow: 0 14px 30px rgba(185, 28, 28, 0.28);
}
.logout-btn-danger:hover:not(:disabled) {
  filter: brightness(1.03);
  box-shadow: 0 18px 40px rgba(185, 28, 28, 0.32);
}

/* Optional: icon kecil di tombol */
.logout-btn .icon {
  width: 16px;
  height: 16px;
  display: inline-block;
}
  </style>

</head>

<body class="font-[Inter] antialiased">

@php
  $user = auth()->user();
  $avatarUrl = $user->photo_url ?? asset('images/default-avatar.png');
@endphp

<div id="wrapper" class="flex">
  {{-- SIDEBAR DEVELOPER --}}
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

    <div class="settings-wrapper">

      {{-- Header title + user mini card --}}
      <div class="settings-header">
        <h1>Pengaturan Akun</h1>

        <div class="settings-user">
          <img
            src="{{ $avatarUrl }}"
            alt="Foto {{ $user->name }}"
            class="settings-user-avatar"
            id="dev-header-avatar"
          >
          <div>
            <div class="settings-user-name">{{ $user->name }}</div>
            <div class="settings-user-role">Developer</div>
          </div>
        </div>
      </div>

      {{-- Card utama pengaturan --}}
      <section class="settings-card">
        <h2 class="settings-card-title">Pengaturan Akun</h2>

        @if (session('status'))
          <div style="padding:8px 10px;border-radius:8px;background:#ecfdf3;color:#166534;font-size:13px;margin-bottom:10px;">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div style="padding:8px 10px;border-radius:8px;background:#fef2f2;color:#b91c1c;font-size:13px;margin-bottom:10px;">
            @foreach ($errors->all() as $e)
              <div>• {{ $e }}</div>
            @endforeach
          </div>
        @endif

        <form
          class="settings-form"
          method="POST"
          action="{{ route('developer.settings.update') }}"
          enctype="multipart/form-data"
        >
          @csrf
          @method('PUT')

          {{-- Foto + tombol --}}
          <div class="settings-photo-box">
            <img
              src="{{ $avatarUrl }}"
              alt="Foto {{ $user->name }}"
              class="settings-photo-preview"
              id="photo-preview"
            >

            <button type="button" class="settings-photo-btn" id="btn-change-photo" disabled>
              Ubah Foto
            </button>
            <input type="file" name="photo" id="photo-input" accept="image/*" style="display:none;">
            <div id="photo-filename" class="settings-help"></div>
          </div>

          {{-- Nama --}}
          <div class="settings-field">
            <label class="settings-label" for="name">Nama</label>
            <input id="name" type="text" name="name" class="settings-input"
                   value="{{ old('name', $user->name) }}" required disabled>
          </div>

          {{-- Email --}}
          <div class="settings-field">
            <label class="settings-label" for="email">Email</label>
            <input id="email" type="email" name="email" class="settings-input"
                   value="{{ old('email', $user->email) }}" required disabled>
          </div>

          {{-- No Telepon --}}
          <div class="settings-field">
            <label class="settings-label" for="phone">No Telepon (Opsional)</label>
            <input id="phone" type="text" name="phone" class="settings-input"
                   value="{{ old('phone', $user->phone ?? '') }}"
                   placeholder="Masukkan nomor telepon aktif" disabled>
          </div>

          {{-- Tombol bawah --}}
          <div class="settings-actions">
            <button type="button" class="settings-btn-edit" id="btn-edit">Edit Profil</button>
            <button type="submit" class="settings-btn-save" id="btn-save" disabled>Simpan Perubahan</button>
          </div>
        </form>

        {{-- ✅ LOGOUT: diletakkan tepat DI BAWAH PROFIL (masih 1 card) --}}
        <hr style="margin:18px 0;border:none;border-top:1px solid #e5e7eb;">

        <div>
          <div style="font-size:14px;font-weight:800;margin-bottom:6px;">Keluar Akun</div>

          <button type="button" id="btnOpenLogoutModal" class="btn-logout">
            Logout
          </button>
        </div>

      </section>
    </div>
  </main>
</div>

{{-- ====== MODAL KONFIRMASI LOGOUT ====== --}}
<div id="logoutModal" class="logout-modal is-hidden" aria-hidden="true">
  <div class="logout-modal__backdrop"></div>

  <div class="logout-modal__panel" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
    <h3 id="logoutTitle" class="logout-modal__title">Konfirmasi Logout</h3>
    <p class="logout-modal__desc">Untuk keluar, ketik <b>KELUAR</b> pada kotak di bawah ini.</p>

    <input type="text" id="logoutConfirmInput" class="logout-modal__input" placeholder="Ketik: KELUAR" autocomplete="off">

    <div class="logout-modal__actions">
      <button type="button" id="btnCancelLogout" class="logout-btn logout-btn-cancel">
        Batal
      </button>
    
      <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="submit" id="btnConfirmLogout" class="logout-btn logout-btn-danger" disabled>
          Ya, Logout
        </button>
      </form>
    </div>

    <div id="logoutError" class="logout-modal__error is-hidden">
      Teks konfirmasi belum sesuai. Ketik <b>KELUAR</b>.
    </div>
  </div>
</div>

{{-- MODAL PREVIEW AVATAR --}}
<div id="avatarModal" class="avatar-modal-backdrop avatar-modal-hidden">
  <div class="avatar-modal-inner">
    <button type="button" class="avatar-modal-close" id="avatarModalClose">&times;</button>
    <img src="{{ $avatarUrl }}" alt="Preview Foto" class="avatar-modal-img" id="avatarModalImg">
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ====== EDIT PROFILE + UPLOAD PHOTO ======
    const btnEdit        = document.getElementById('btn-edit');
    const btnSave        = document.getElementById('btn-save');
    const btnChangePhoto = document.getElementById('btn-change-photo');
    const inputPhoto     = document.getElementById('photo-input');
    const labelFile      = document.getElementById('photo-filename');
    const formInputs     = document.querySelectorAll('.settings-input');
    const photoPreview   = document.getElementById('photo-preview');
    const headerAvatar   = document.getElementById('dev-header-avatar');

    // ====== MODAL AVATAR ======
    const avatarModal      = document.getElementById('avatarModal');
    const avatarModalImg   = document.getElementById('avatarModalImg');
    const avatarModalClose = document.getElementById('avatarModalClose');

    let editing = false;

    if (btnChangePhoto && inputPhoto) {
      btnChangePhoto.addEventListener('click', function () {
        if (!btnChangePhoto.disabled) inputPhoto.click();
      });

      inputPhoto.addEventListener('change', function () {
        if (inputPhoto.files && inputPhoto.files[0]) {
          const file = inputPhoto.files[0];
          if (labelFile) labelFile.textContent = 'File dipilih: ' + file.name;

          const reader = new FileReader();
          reader.onload = function (e) {
            if (photoPreview) photoPreview.src = e.target.result;
            if (avatarModalImg) avatarModalImg.src = e.target.result;
          };
          reader.readAsDataURL(file);
        } else {
          if (labelFile) labelFile.textContent = '';
        }
      });
    }

    if (btnEdit) {
      btnEdit.addEventListener('click', function () {
        editing = !editing;

        formInputs.forEach(function (el) { el.disabled = !editing; });

        if (btnSave) btnSave.disabled = !editing;
        if (btnChangePhoto) btnChangePhoto.disabled = !editing;

        btnEdit.textContent = editing ? 'Batalkan' : 'Edit Profil';

        if (!editing) {
          if (labelFile) labelFile.textContent = '';
          if (inputPhoto) inputPhoto.value = '';
        }
      });
    }

    function openAvatarModal(src) {
      if (!avatarModal || !avatarModalImg) return;
      avatarModalImg.src = src;
      avatarModal.classList.remove('avatar-modal-hidden');
    }
    function closeAvatarModal() {
      if (!avatarModal) return;
      avatarModal.classList.add('avatar-modal-hidden');
    }

    if (photoPreview) photoPreview.addEventListener('click', () => photoPreview.src && openAvatarModal(photoPreview.src));
    if (headerAvatar) headerAvatar.addEventListener('click', () => headerAvatar.src && openAvatarModal(headerAvatar.src));

    if (avatarModalClose) avatarModalClose.addEventListener('click', function (e) {
      e.stopPropagation(); closeAvatarModal();
    });
    if (avatarModal) avatarModal.addEventListener('click', function (e) {
      if (e.target === avatarModal) closeAvatarModal();
    });

    // ====== MODAL LOGOUT + VALIDASI TEXTBOX ======
    const logoutModal  = document.getElementById('logoutModal');
    const openLogout   = document.getElementById('btnOpenLogoutModal');
    const cancelLogout = document.getElementById('btnCancelLogout');
    const logoutInput  = document.getElementById('logoutConfirmInput');
    const logoutBtn    = document.getElementById('btnConfirmLogout');
    const logoutErr    = document.getElementById('logoutError');
    const logoutForm   = document.getElementById('logoutForm');

    function openLogoutModal() {
      if (!logoutModal) return;
      logoutModal.classList.remove('is-hidden');
      logoutModal.setAttribute('aria-hidden', 'false');

      if (logoutInput) logoutInput.value = '';
      if (logoutBtn) logoutBtn.disabled = true;
      if (logoutErr) logoutErr.classList.add('is-hidden');

      setTimeout(() => logoutInput && logoutInput.focus(), 50);
    }
    function closeLogoutModal() {
      if (!logoutModal) return;
      logoutModal.classList.add('is-hidden');
      logoutModal.setAttribute('aria-hidden', 'true');
    }

    if (openLogout) openLogout.addEventListener('click', openLogoutModal);
    if (cancelLogout) cancelLogout.addEventListener('click', closeLogoutModal);

    if (logoutModal) {
      logoutModal.addEventListener('click', function(e){
        if (e.target.classList.contains('logout-modal__backdrop')) closeLogoutModal();
      });
    }

    if (logoutInput) {
      logoutInput.addEventListener('input', function(){
        const v = (logoutInput.value || '').trim().toUpperCase();
        const ok = (v === 'KELUAR');
        if (logoutBtn) logoutBtn.disabled = !ok;
        if (ok && logoutErr) logoutErr.classList.add('is-hidden');
      });
    }

    if (logoutForm) {
      logoutForm.addEventListener('submit', function(e){
        const v = (logoutInput?.value || '').trim().toUpperCase();
        if (v !== 'KELUAR') {
          e.preventDefault();
          if (logoutErr) logoutErr.classList.remove('is-hidden');
          if (logoutBtn) logoutBtn.disabled = true;
          if (logoutInput) logoutInput.focus();
        }
      });
    }

    // ESC global
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        if (avatarModal && !avatarModal.classList.contains('avatar-modal-hidden')) closeAvatarModal();
        if (logoutModal && !logoutModal.classList.contains('is-hidden')) closeLogoutModal();
      }
    });
  });
</script>

</body>
</html>