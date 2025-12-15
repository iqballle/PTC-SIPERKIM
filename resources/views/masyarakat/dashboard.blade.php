<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Masyarakat — SIPERKIM</title>
  
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="{{ asset('css/masyarakat-dashboard.css') }}">
  
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
  
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
  
    <style>
      :root {
        /* Font utama SIPERKIM */
        --font-siperkim: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
                         'Segoe UI', sans-serif;
      }
  
      body {
        font-family: var(--font-siperkim);
      }

      /* kecil-kecil tambahan */
      .perumahan-card img {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
      }
    </style>
  </head>
  
  <body class="bg-gray-100">

  {{-- NAVBAR MASYARAKAT --}}
  @include('layouts.masyarakat-navbar', ['active' => 'home'])

  <!-- HERO / BANNER -->
  <section class="relative h-[380px] sm:h-[420px] lg:h-[450px] bg-cover bg-center"
           style="background-image:url('{{ asset('images/hero.jpg') }}');">
    <!-- Overlay gelap -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Konten hero -->
    <div class="relative z-10 max-w-6xl mx-auto h-full flex flex-col items-center justify-center px-4 text-center text-white">
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-wide">
        SELAMAT DATANG DI SIPERKIM
      </h1>
      <p class="mt-4 max-w-2xl text-sm sm:text-base lg:text-lg">
        CARI PERUMAHAN SESUAI KEINGINAN ANDA DENGAN CEPAT, 
        DILENGKAPI SISTEM MONITORING RTH BERBASIS IoT.
      </p>
    </div>
  </section>

  <!-- FORM CARI PERUMAHAN (card yang nempel di bawah banner) -->
  <section class="relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="-mt-16 sm:-mt-20 bg-white shadow-lg rounded-xl border border-gray-100 px-4 sm:px-6 py-5">
        <h2 class="text-center text-sm font-semibold tracking-wide mb-4">
          CARI PERUMAHAN
        </h2>

        <form action="{{ route('perumahan.index') }}" method="GET" class="space-y-4">
          <!-- Baris 1 -->
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Nama Perumahan</label>
              <input type="text" name="nama" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="Sunrise City" value="{{ request('nama') }}">
            </div>
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Lokasi</label>
              <input type="text" name="lokasi" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="Parepare" value="{{ request('lokasi') }}">
            </div>
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Tipe Rumah</label>
              <input type="text" name="tipe" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="36/72" value="{{ request('tipe') }}">
            </div>
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Status Unit</label>
              <select name="status" class="w-full border rounded-md px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="Tersedia" @selected(request('status')=='Tersedia')>Tersedia</option>
                <option value="Terjual" @selected(request('status')=='Terjual')>Terjual</option>
              </select>
            </div>
          </div>

          <!-- Baris 2 -->
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Harga Min</label>
              <input type="number" name="harga_min" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="150000000" value="{{ request('harga_min') }}">
            </div>
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Harga Max</label>
              <input type="number" name="harga_max" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="500000000" value="{{ request('harga_max') }}">
            </div>
            <div>
              <label class="block text-[11px] font-semibold mb-1 uppercase tracking-wide text-gray-600">Fasilitas</label>
              <input type="text" name="fasilitas" class="w-full border rounded-md px-3 py-2 text-sm"
                     placeholder="taman, mushola, dll" value="{{ request('fasilitas') }}">
            </div>
            <div class="flex justify-end">
              <button type="submit"
                      class="w-full sm:w-auto mt-2 sm:mt-0 bg-[#9C2F21] text-white px-6 py-2.5 rounded-md text-sm font-semibold hover:bg-[#7f261a]">
                CARI
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- DAFTAR PERUMAHAN -->
  <section class="py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <h2 class="text-sm font-semibold text-center tracking-wide mb-2">DAFTAR PERUMAHAN</h2>

      @if($perumahans->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
          @foreach ($perumahans as $perumahan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
              <!-- Gambar -->
              @if($perumahan->cover_url ?? false)
                <img src="{{ $perumahan->cover_url }}" alt="{{ $perumahan->nama }}"
                     class="w-full h-44 object-cover">
              @else
                <img src="{{ asset('images/default-perumahan.jpg') }}" alt="{{ $perumahan->nama }}"
                     class="w-full h-44 object-cover">
              @endif

              <!-- Konten -->
              <div class="p-4 text-sm flex flex-col flex-1">
                <div class="font-semibold text-base mb-1">{{ $perumahan->nama }}</div>

                @if($perumahan->nama_perusahaan)
                  <div class="text-[11px] text-gray-600 uppercase tracking-wide mb-1">
                    {{ $perumahan->nama_perusahaan }}
                  </div>
                @endif

                <div class="text-xs text-gray-700 mb-2">
                  {{ $perumahan->lokasi }}
                </div>

                @if($perumahan->harga)
                  <div class="text-xs mb-1">
                    <span class="font-semibold">
                      Mulai Rp {{ number_format($perumahan->harga,0,',','.') }}
                    </span>
                  </div>
                @endif

                {{-- STATUS UNIT (bukan status verifikasi) --}}
                @php
                    $rawStatusUnit = $perumahan->status_unit ?? 'Tersedia';

                    if (in_array($rawStatusUnit, ['tersedia', 'Tersedia'])) {
                        $statusUnitLabel = 'Tersedia';
                        $statusUnitClass = 'bg-green-100 text-green-700';
                    } elseif (in_array($rawStatusUnit, ['tidak_tersedia', 'Terjual'])) {
                        $statusUnitLabel = 'Tidak tersedia';
                        $statusUnitClass = 'bg-red-100 text-red-700';
                    } else {
                        $statusUnitLabel = $rawStatusUnit;
                        $statusUnitClass = 'bg-gray-100 text-gray-700';
                    }
                @endphp

                <div class="text-xs text-gray-700 mb-2 flex items-center flex-wrap gap-2">
                  <span>Status unit:</span>
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $statusUnitClass }}">
                    {{ $statusUnitLabel }}
                  </span>

                  @if($perumahan->tipe)
                    <span class="mx-1 text-gray-300 hidden sm:inline">|</span>
                    <span>Tipe : <span class="font-semibold">{{ $perumahan->tipe }}</span></span>
                  @endif
                </div>

                <div class="mt-auto pt-2 text-right">
                  <a href="{{ route('perumahan.show', $perumahan->id) }}"
                     class="text-[11px] font-semibold tracking-wide text-[#9C2F21] uppercase">
                    Selengkapnya
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="text-center text-gray-500 mt-6">Belum ada data perumahan.</p>
      @endif
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-[#9C2F21] text-white mt-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
      <!-- Kolom kiri -->
      <div>
        <div class="flex items-center mb-3">
          <img src="{{ asset('images/logo-siperkim.png') }}" class="w-10 h-10 mr-2" alt="">
          <span class="font-bold text-lg">SIPERKIM</span>
        </div>
        <p class="text-xs leading-relaxed">
          SIPERKIM merupakan sistem informasi Perumahan dan kawasan Pemukiman di Kota Parepare,
          yang didampingi dengan Sistem Monitoring RTH berbasis IoT.
        </p>
      </div>

      <!-- Navigasi -->
      <div>
        <h3 class="font-semibold mb-2 text-sm">NAVIGASI</h3>
        <ul class="space-y-1 text-xs">
          <li><a href="{{ route('home') }}" class="hover:underline">Beranda</a></li>
          <li><a href="{{ route('perumahan.index') }}" class="hover:underline">Daftar Perumahan</a></li>
          <li><a href="{{ route('about') }}" class="hover:underline">Tentang Kami</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div>
        <h3 class="font-semibold mb-2 text-sm">Kontak Kami</h3>
        <p class="text-xs">
          BTN Sunrise Blok K1<br>
          0812 4234 556<br>
          Senin – Jumat 09.00–14.00
        </p>
      </div>
    </div>

    <div class="border-t border-white/20">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 text-[11px] text-center">
        &copy; 2025 SIPERKIM Kota Parepare. Dikembangkan oleh Kelompok TUMBUH – Dinas Perkimtan Kota Parepare
      </div>
    </div>
  </footer>

  {{-- SCRIPT POPUP HUBUNGI KAMI --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btn  = document.getElementById('contactMenuButton');
      const menu = document.getElementById('contactMenu');

      if (!btn || !menu) return;

      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
      });

      document.addEventListener('click', function (e) {
        if (!menu.classList.contains('hidden')) {
          if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.add('hidden');
          }
        }
      });
    });
  </script>

</body>
</html>
