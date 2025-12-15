<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Perumahan — SIPERKIM</title>

    <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/masyarakat-dashboard.css') }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">


  {{-- NAVBAR MASYARAKAT (otomatis aktif di DAFTAR PERUMAHAN) --}}
  @include('layouts.masyarakat-navbar')

  {{-- HERO DAFTAR PERUMAHAN --}}
  <section class="relative h-[260px] sm:h-[280px] lg:h-[300px] bg-cover bg-center"
           style="background-image:url('{{ asset('images/hero.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 max-w-6xl mx-auto h-full flex flex-col justify-center px-4 text-center text-white">
      <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-wide mb-4">
        DAFTAR PERUMAHAN
      </h1>

      {{-- Kotak pencarian satu baris --}}
      <form action="{{ route('perumahan.index') }}" method="GET"
            class="max-w-xl mx-auto">
        <div class="flex items-stretch bg-white rounded-full shadow-md overflow-hidden">
          <input type="text" name="q"
                 class="flex-1 px-4 py-2 text-sm sm:text-base text-gray-800 outline-none border-none"
                 placeholder="Nama Perumahan / lokasi / kec / kel ..."
                 value="{{ request('q') }}">
          <button type="submit"
                  class="px-4 sm:px-5 bg-[#9C2F21] text-white text-sm font-semibold flex items-center justify-center">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            Cari
          </button>
        </div>
      </form>
    </div>
  </section>

  {{-- DAFTAR PERUMAHAN --}}
  <section class="py-10 sm:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      {{-- Grid perumahan --}}
      @if($perumahans->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
          @foreach ($perumahans as $perumahan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
              {{-- Gambar --}}
              @if($perumahan->cover_url ?? false)
                <img src="{{ $perumahan->cover_url }}" alt="{{ $perumahan->nama }}"
                     class="w-full h-48 object-cover rounded-t-xl">
              @else
                <img src="{{ asset('images/default-perumahan.jpg') }}" alt="{{ $perumahan->nama }}"
                     class="w-full h-48 object-cover rounded-t-xl">
              @endif

              {{-- Isi kartu --}}
              <div class="p-4 text-sm flex flex-col flex-1">
                <div class="font-semibold text-base mb-1">{{ $perumahan->nama }}</div>
                @if($perumahan->nama_perusahaan)
                  <div class="text-[11px] text-gray-600 uppercase tracking-wide mb-1">
                    {{ $perumahan->nama_perusahaan }}
                  </div>
                @endif>

                <div class="text-xs text-gray-700 mb-2">
                  {{ $perumahan->lokasi }}
                </div>

                @if($perumahan->harga)
                  <div class="text-xs mb-1">
                    Mulai <span class="font-semibold">
                      Rp {{ number_format($perumahan->harga,0,',','.') }}
                    </span>
                  </div>
                @endif

                <div class="text-xs text-gray-700 mb-2">
                  Status: <span class="font-semibold">{{ $perumahan->status ?? 'Tersedia' }}</span>
                  @if($perumahan->tipe)
                    &nbsp;|&nbsp; Tipe: <span class="font-semibold">{{ $perumahan->tipe }}</span>
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

        {{-- Pagination --}}
        <div class="mt-6">
          {{ $perumahans->links() }}
        </div>
      @else
        <p class="text-center text-gray-500 mt-6">
          Belum ada perumahan yang disetujui atau sesuai kata kunci.
        </p>
      @endif
    </div>
  </section>

  {{-- FOOTER --}}
  @include('layouts.masyarakat-footer')

</body>
</html>