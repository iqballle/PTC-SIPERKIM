{{-- resources/views/dinas/settings/index.blade.php --}}
@extends('layouts.dinas')

@section('title', 'Pengaturan Akun — SIPERKIM Dinas')

@push('styles')
<style>
  .dinas-settings-wrapper{ max-width:920px; margin:0 auto; }
  .dinas-settings-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
  .dinas-settings-header h1{ margin:0; font-size:28px; font-weight:800; }
  .dinas-settings-user{ display:flex; align-items:center; gap:12px; }
  .dinas-settings-avatar{
    width:60px; height:60px; border-radius:9999px; object-fit:cover;
    background:#e5e7eb; cursor:pointer;
  }
  .dinas-settings-user-name{ font-weight:700; font-size:15px; }
  .dinas-settings-user-role{ font-size:13px; color:#6b7280; margin-top:2px; }

  .dinas-settings-card{
    background:#ffffff; border-radius:18px; border:1px solid #e5e7eb;
    padding:28px 32px 26px; box-shadow:0 10px 30px rgba(15,23,42,0.06);
  }
  .dinas-settings-card-title{ font-size:20px; font-weight:700; margin:0 0 20px; }

  .dinas-settings-photo-box{
    display:flex; flex-direction:column; align-items:center;
    margin-bottom:20px; margin-top:6px; gap:6px;
  }
  .dinas-settings-photo-preview{
    width:88px; height:88px; border-radius:9999px; object-fit:cover;
    background:#e5e7eb; border:3px solid #e5e7eb; margin-bottom:6px; cursor:pointer;
  }
  .dinas-settings-photo-btn{
    border-radius:9999px; border:1px solid #d1d5db; background:#ffffff;
    padding:8px 22px; font-size:13px; font-weight:600; cursor:pointer;
  }
  .dinas-settings-photo-btn:disabled{ cursor:not-allowed; opacity:.6; }
  .dinas-settings-photo-btn:hover:not(:disabled){ background:#f3f4f6; }

  .dinas-settings-form{ max-width:520px; margin:0 auto; }
  .dinas-settings-field{ margin-bottom:14px; }
  .dinas-settings-label{ display:block; font-size:14px; font-weight:600; margin-bottom:6px; }
  .dinas-settings-input{
    width:100%; border-radius:8px; border:1px solid #d1d5db;
    padding:10px 12px; font-size:14px; outline:none; background:#ffffff;
    transition:border-color .15s, box-shadow .15s, background .15s;
  }
  .dinas-settings-input:focus{ border-color:#546A2C; box-shadow:0 0 0 1px rgba(84,106,44,0.25); }
  .dinas-settings-input[disabled]{ background:#f9fafb; color:#6b7280; cursor:not-allowed; }

  .dinas-settings-actions{ display:flex; gap:14px; margin-top:18px; }
  .dinas-settings-actions button{
    flex:1; border:none; border-radius:8px; padding:10px 14px;
    font-size:14px; font-weight:600; cursor:pointer;
    transition:filter .15s, opacity .15s;
  }
  .dinas-settings-btn-edit{ background:#546A2C; color:#ffffff; }
  .dinas-settings-btn-save{ background:#4b8f3a; color:#ffffff; }
  .dinas-settings-actions button:hover:not(:disabled){ filter:brightness(1.05); }
  .dinas-settings-actions button:disabled{ opacity:.55; cursor:not-allowed; }

  .dinas-settings-help{ font-size:12px; color:#6b7280; margin-top:2px; text-align:center; }

  /* ===== MODAL PREVIEW AVATAR ===== */
  .avatar-modal-backdrop{
    position:fixed; inset:0; background:rgba(0,0,0,0.75);
    display:flex; align-items:center; justify-content:center; z-index:9999;
  }
  .avatar-modal-inner{ position:relative; max-width:90vw; max-height:90vh; }
  .avatar-modal-img{
    max-width:100%; max-height:90vh; border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,0.55); display:block;
  }
  .avatar-modal-close{
    position:absolute; top:-12px; right:-12px; width:34px; height:34px;
    border-radius:9999px; border:none; background:#111827; color:#f9fafb;
    font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center;
  }
  .avatar-modal-hidden{ display:none !important; }

  @media (max-width:768px){
    .dinas-settings-header{ flex-direction:column; align-items:flex-start; gap:12px; }
    .dinas-settings-card{ padding:20px 16px 18px; }
    .dinas-settings-form{ max-width:100%; }
    .dinas-settings-actions{ flex-direction:column; }
  }

  /* ===== LOGOUT MODAL (SAMAKAN DENGAN DEVELOPER) ===== */
  .logout-modal { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; }
  .logout-modal.is-hidden { display:none; }
  .logout-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.55); }
  .logout-modal__panel{
    position:relative; width:min(520px, 92vw);
    background:#fff; border-radius:14px; padding:16px 16px 14px;
    box-shadow:0 20px 60px rgba(0,0,0,.25);
    border:1px solid #e5e7eb;
  }
  .logout-modal__title{ margin:0; font-size:15px; font-weight:800; }
  .logout-modal__desc{ margin:6px 0 10px; font-size:13px; color:#6b7280; }
  .logout-modal__input{
    width:100%; border:1px solid #d1d5db; border-radius:10px;
    padding:10px 12px; font-size:13px; outline:none;
  }
  .logout-modal__input:focus{ border-color:#b91c1c; box-shadow:0 0 0 1px rgba(185, 28, 28, .25); }
  .logout-modal__actions{ display:flex; justify-content:flex-end; gap:10px; margin-top:12px; }
  .logout-modal__error{
    margin-top:10px; font-size:12px; color:#b91c1c;
    background:#fef2f2; padding:8px 10px; border-radius:10px; border:1px solid #fecaca;
  }

  /* pastikan class ini ada untuk hide error */
  .is-hidden { display:none !important; }

  @media (max-width:520px){
    .logout-modal__actions{ flex-direction:column; }
    .logout-modal__actions .btn-plain,
    .logout-modal__actions .btn-primary{ width:100%; }
  }
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
</style>
@endpush

@section('content')
  @php
    /** @var \App\Models\User $user */
    $avatarUrl = $user->photo_url ?? asset('images/default-avatar.png');
  @endphp

  <div class="dinas-settings-wrapper">
    {{-- Header --}}
    <div class="dinas-settings-header">
      <h1>Pengaturan Akun</h1>

      <div class="dinas-settings-user">
        <img src="{{ $avatarUrl }}" alt="Foto {{ $user->name }}" class="dinas-settings-avatar" id="dinas-header-avatar">
        <div>
          <div class="dinas-settings-user-name">{{ $user->name }}</div>
          <div class="dinas-settings-user-role">Petugas Dinas</div>
        </div>
      </div>
    </div>

    {{-- Card utama --}}
    <section class="dinas-settings-card">
      <h2 class="dinas-settings-card-title">Pengaturan Akun</h2>

      @if (session('status'))
        <div style="padding:8px 10px;border-radius:8px;background:#ecfdf3;color:#166534;font-size:13px;margin-bottom:10px;">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div style="padding:8px 10px;border-radius:8px;background:#fef2f2;color:#b91c1c;font-size:13px;margin-bottom:10px;">
          @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form class="dinas-settings-form" method="POST" action="{{ route('dinas.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="dinas-settings-photo-box">
          <img src="{{ $avatarUrl }}" alt="Foto {{ $user->name }}" class="dinas-settings-photo-preview" id="photo-preview">

          <button type="button" class="dinas-settings-photo-btn" id="btn-change-photo" disabled>
            Ubah Foto
          </button>

          <input type="file" name="photo" id="photo-input" accept="image/*" style="display:none;">
          <div id="photo-filename" class="dinas-settings-help"></div>
        </div>

        <div class="dinas-settings-field">
          <label class="dinas-settings-label" for="name">Nama</label>
          <input id="name" type="text" name="name" class="dinas-settings-input" value="{{ old('name', $user->name) }}" required disabled>
        </div>

        <div class="dinas-settings-field">
          <label class="dinas-settings-label" for="email">Email</label>
          <input id="email" type="email" name="email" class="dinas-settings-input" value="{{ old('email', $user->email) }}" required disabled>
        </div>

        <div class="dinas-settings-field">
          <label class="dinas-settings-label" for="phone">No Telepon (Opsional)</label>
          <input id="phone" type="text" name="phone" class="dinas-settings-input"
                 value="{{ old('phone', $user->phone ?? '') }}"
                 placeholder="Masukkan nomor telepon kantor / pribadi" disabled>
        </div>

        <div class="dinas-settings-actions">
          <button type="button" class="dinas-settings-btn-edit" id="btn-edit">Edit Profil</button>
          <button type="submit" class="dinas-settings-btn-save" id="btn-save" disabled>Simpan Perubahan</button>
        </div>
      </form>

      {{-- ✅ LOGOUT: tepat di bawah profil (masih dalam 1 card) --}}
      <hr style="margin:18px 0;border:none;border-top:1px solid #e5e7eb;">

      <div>
        <div style="font-size:14px;font-weight:800;margin-bottom:6px;">Keluar Akun</div>

        <button type="button" id="btnOpenLogoutModal" class="btn-logout">
          Logout
        </button>
      </div>
    </section>
  </div>

  {{-- MODAL KONFIRMASI LOGOUT --}}
  <div id="logoutModal" class="logout-modal is-hidden" aria-hidden="true">
    <div class="logout-modal__backdrop"></div>

    <div class="logout-modal__panel" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
      <h3 id="logoutTitle" class="logout-modal__title">Konfirmasi Logout</h3>
      <p class="logout-modal__desc">
        Untuk keluar, ketik <b>KELUAR</b> pada kotak di bawah ini.
      </p>

      <input type="text" id="logoutConfirmInput" class="logout-modal__input" placeholder="Ketik: KELUAR" autocomplete="off">

      <div class="logout-modal__actions">
        <button type="button" id="btnCancelLogout" class="btn-plain">
          Batal
        </button>

        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
          @csrf
          <button
            type="submit"
            id="btnConfirmLogout"
            class="btn-primary"
            disabled
            style="background:#b91c1c;"
          >
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
      const btnEdit        = document.getElementById('btn-edit');
      const btnSave        = document.getElementById('btn-save');
      const btnChangePhoto = document.getElementById('btn-change-photo');
      const inputPhoto     = document.getElementById('photo-input');
      const labelFile      = document.getElementById('photo-filename');
      const photoPreview   = document.getElementById('photo-preview');
      const formInputs     = document.querySelectorAll('.dinas-settings-input');
      const headerAvatar   = document.getElementById('dinas-header-avatar');

      // modal avatar
      const modal      = document.getElementById('avatarModal');
      const modalImg   = document.getElementById('avatarModalImg');
      const modalClose = document.getElementById('avatarModalClose');

      let editing = false;

      // pilih foto
      if (btnChangePhoto && inputPhoto) {
        btnChangePhoto.addEventListener('click', function () {
          if (!btnChangePhoto.disabled) inputPhoto.click();
        });

        inputPhoto.addEventListener('change', function () {
          if (inputPhoto.files && inputPhoto.files[0]) {
            const file = inputPhoto.files[0];
            labelFile.textContent = 'File dipilih: ' + file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
              if (photoPreview) photoPreview.src = e.target.result;
              if (modalImg) modalImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
          } else {
            labelFile.textContent = '';
          }
        });
      }

      // toggle edit
      if (btnEdit) {
        btnEdit.addEventListener('click', function () {
          editing = !editing;

          formInputs.forEach(function (el) { el.disabled = !editing; });

          btnSave.disabled        = !editing;
          btnChangePhoto.disabled = !editing;

          btnEdit.textContent = editing ? 'Batalkan' : 'Edit Profil';

          if (!editing) {
            if (labelFile) labelFile.textContent = '';
            if (inputPhoto) inputPhoto.value = '';
          }
        });
      }

      // modal zoom avatar
      function openAvatarModal(src) {
        if (!modal || !modalImg) return;
        modalImg.src = src;
        modal.classList.remove('avatar-modal-hidden');
      }
      function closeAvatarModal() {
        if (!modal) return;
        modal.classList.add('avatar-modal-hidden');
      }

      if (photoPreview) {
        photoPreview.addEventListener('click', function () {
          if (photoPreview.src) openAvatarModal(photoPreview.src);
        });
      }
      if (headerAvatar) {
        headerAvatar.addEventListener('click', function () {
          if (headerAvatar.src) openAvatarModal(headerAvatar.src);
        });
      }
      if (modalClose) {
        modalClose.addEventListener('click', function (e) { e.stopPropagation(); closeAvatarModal(); });
      }
      if (modal) {
        modal.addEventListener('click', function (e) { if (e.target === modal) closeAvatarModal(); });
      }
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('avatar-modal-hidden')) closeAvatarModal();
      });

      // ===== Logout modal =====
      const logoutModal  = document.getElementById('logoutModal');
      const openLogout   = document.getElementById('btnOpenLogoutModal');
      const cancelLogout = document.getElementById('btnCancelLogout');
      const inputLogout  = document.getElementById('logoutConfirmInput');
      const btnConfirm   = document.getElementById('btnConfirmLogout');
      const err          = document.getElementById('logoutError');
      const formLogout   = document.getElementById('logoutForm');

      if (logoutModal && openLogout && cancelLogout && inputLogout && btnConfirm && formLogout) {
        function openModal() {
          logoutModal.classList.remove('is-hidden');
          logoutModal.setAttribute('aria-hidden','false');
          inputLogout.value = '';
          btnConfirm.disabled = true;
          err.classList.add('is-hidden');
          setTimeout(()=>inputLogout.focus(), 50);
        }
        function closeModal() {
          logoutModal.classList.add('is-hidden');
          logoutModal.setAttribute('aria-hidden','true');
        }

        openLogout.addEventListener('click', openModal);
        cancelLogout.addEventListener('click', closeModal);

        logoutModal.addEventListener('click', function(e){
          if (e.target.classList.contains('logout-modal__backdrop')) closeModal();
        });

        document.addEventListener('keydown', function(e){
          if (e.key === 'Escape' && !logoutModal.classList.contains('is-hidden')) closeModal();
        });

        inputLogout.addEventListener('input', function(){
          const v = (inputLogout.value || '').trim().toUpperCase();
          const ok = (v === 'KELUAR');
          btnConfirm.disabled = !ok;
          if (ok) err.classList.add('is-hidden');
        });

        formLogout.addEventListener('submit', function(e){
          const v = (inputLogout.value || '').trim().toUpperCase();
          if (v !== 'KELUAR') {
            e.preventDefault();
            err.classList.remove('is-hidden');
            btnConfirm.disabled = true;
            inputLogout.focus();
          }
        });
      }
    });
  </script>
@endsection