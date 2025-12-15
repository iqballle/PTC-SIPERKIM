<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing Page — SIPERKIM</title>
  
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
  
    <!-- Tailwind CSS CDN (kalau kamu pakai Tailwind di landing) -->
    <script src="https://cdn.tailwindcss.com"></script>
  
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
  
    <style>
      :root {
        --font-siperkim: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
                         'Segoe UI', sans-serif;
      }
  
      body {
        font-family: var(--font-siperkim);
      }
    </style>
  </head>
<body class="bg-white text-gray-900 antialiased font-[Inter]">

  {{-- HERO --}}
  <header class="hero relative">
    <div class="hero__overlay"></div>
    <img class="hero__bg" src="{{ asset('images/hero.jpg') }}" alt="Deretan rumah di Parepare">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24 lg:py-28 relative">
      <div class="max-w-3xl">
        <h1 class="text-white text-3xl sm:text-5xl font-extrabold leading-tight drop-shadow-xl">
          Sistem Informasi Perumahan kota
          Parepare dan Monitoring RTH berbasis
          IoT Kota Parepare
        </h1>
        <p class="mt-4 text-white/90 text-base sm:text-lg max-w-2xl drop-shadow">
          Menyediakan data Perumahan Secara Real time dan mudah di akses,
          di sertai monitoring ruang terbuka hijau.
        </p>

        <div class="mt-8 flex flex-wrap gap-4">
          <!-- Tombol Lihat Perumahan -->
          <a href="{{ route('masyarakat.dashboard') }}" class="btn btn--light">Lihat Perumahan</a>

          <!-- Tombol Masuk Developer -->
          <a href="{{ route('developer.login') }}" class="btn btn--primary">Masuk Developer</a>
        </div>
      </div>
    </div>
  </header>

  {{-- PILIH PERAN --}}
  <section class="py-12 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <h2 class="text-3xl sm:text-4xl font-extrabold">Masuk Sebagai  </h2>

      <div class="mt-8 grid gap-6 sm:grid-cols-3">
        {{-- MASYARAKAT --}}
        <div class="card">
          <img src="{{ asset('images/role-masyarakat.png') }}" alt="Ikon masyarakat" class="w-24 h-24 mx-auto">
          <h3 class="card__title">MASYARAKAT</h3>
          <p class="card__desc">
            Jelajahi informasi perumahan di Kota Parepare secara mudah,
            cepat, dan transparan.
          </p>
          <a href="{{ route('masyarakat.dashboard') }}" class="btn btn--light">Lihat Perumahan</a>
        </div>

        {{-- DEVELOPER --}}
        <div class="card">
          <img src="{{ asset('images/role-developer.png') }}" alt="Ikon developer" class="w-24 h-24 mx-auto">
          <h3 class="card__title">DEVELOPER</h3>
          <p class="card__desc">
            Kelola data perumahan Anda, unggah dokumen legalitas, dan
            ajukan permohonan nota dinas secara digital.
          </p>
          <a href="{{ route('developer.login') }}" class="btn btn--primary w-full">
            Masuk Developer
          </a>
        </div>

        {{-- DISPERKIMTAN --}}
        <div class="card">
          <img src="{{ asset('images/role-dinas.png') }}" alt="Ikon dinas" class="w-24 h-24 mx-auto">
          <h3 class="card__title">DISPERKIMTAN</h3>
          <p class="card__desc">
            Akses dan verifikasi data perumahan, developer, serta permohonan
            secara real-time.
          </p>
          <a href="{{ route('dinas.login') }}" class="btn btn--white w-full">
            Masuk Dinas
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="bg-[#9D2B2B] text-white pt-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 grid gap-10 sm:grid-cols-3">
      <div>
        <div class="flex items-center gap-3">
          <img src="{{ asset('images/logo-siperkim.png') }}" alt="Logo SIPERKIM" class="w-12 h-12">
          <span class="text-xl font-bold">SIPERKIM</span>
        </div>
        <p class="mt-4 text-white/90">
          SIPERKIM merupakan sistem informasi Perumahan dan kawasan
          Permukiman di kota PAREPARE, yang di dampingi dengan Sistem
          Monitoring RTH berbasis IoT.
        </p>
      </div>

      <div>
        <h4 class="font-semibold text-lg">NAVIGASI</h4>
        <ul class="mt-3 space-y-2">
          <li><a class="footer__link" href="{{ route('home') }}">Beranda</a></li>
          <li><a class="footer__link" href="{{ route('perumahan.index') }}">Daftar Perumahan</a></li>
          <li><a class="footer__link" href="{{ route('about') }}">Tentang Kami</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold text-lg">Kontak Kami</h4>
        <ul class="mt-3 space-y-1">
          <li>BTN Sunrise Blok k1</li>
          <li>08124234356</li>
          <li>Senin - Jumat 09.00 - 14.00</li>
        </ul>
      </div>
    </div>

    <div class="mt-10 border-t border-white/20">
      <p class="text-center text-sm py-4">
        ©️ 2025 SIPERKIM Kota Parepare. Dikembangkan oleh Kelompok TUMBUH - Dinas Perkimtan Kota Parepare
      </p>
    </div>
  </footer>
</body>
</html>