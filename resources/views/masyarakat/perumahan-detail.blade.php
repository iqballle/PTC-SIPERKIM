<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $perumahan->nama }} — SIPERKIM</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/masyarakat-dashboard.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
                         'Segoe UI', sans-serif;
        }

        /* overlay zoom */
        #imgZoomOverlay {
            backdrop-filter: blur(2px);
        }
    </style>
</head>
<body class="bg-gray-100">

    {{-- NAVBAR --}}
    @include('layouts.masyarakat-navbar')

    {{-- BAR ATAS MERAH --}}
    <div class="bg-[#9C2F21] text-white text-xs sm:text-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-2 flex items-center gap-3">
            <a href="{{ route('masyarakat.dashboard') }}" class="flex items-center gap-1 hover:underline">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
                Dashboard Perumahan
            </a>
            <span class="opacity-70">/</span>
            <a href="{{ route('perumahan.index') }}" class="hover:underline">
                Daftar Perumahan
            </a>
        </div>
    </div>

    {{-- BREADCRUMB HALUS --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-3 text-[11px] text-gray-500">
        <a href="{{ route('perumahan.index') }}" class="hover:underline">
            Daftar Perumahan
        </a>
        <span class="mx-1">/</span>
        <span class="text-gray-700 font-semibold">
            {{ $perumahan->nama }}
        </span>
    </div>

    {{-- KONTEN UTAMA --}}
    <section class="py-4 sm:py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= KOLOM KIRI ================= --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- SLIDER FOTO --}}
                @php
                    $gallery = $perumahan->gallery_urls ?? [];
                    if (empty($gallery) && !empty($perumahan->cover_url ?? null)) {
                        $gallery = [$perumahan->cover_url];
                    }
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4">
                    <div id="perumahanGallery" class="relative">
                        @forelse($gallery as $index => $url)
                            <div data-slide="{{ $index }}" class="{{ $index === 0 ? '' : 'hidden' }}">
                                <img src="{{ $url }}"
                                     alt="Foto {{ $perumahan->nama }} {{ $index+1 }}"
                                     class="w-full h-64 sm:h-72 lg:h-80 object-cover rounded-lg cursor-zoom-in"
                                     data-zoomable>
                            </div>
                        @empty
                            <div class="w-full h-64 sm:h-72 lg:h-80 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">
                                Belum ada foto perumahan.
                            </div>
                        @endforelse

                        @if(count($gallery) > 1)
                            <button type="button"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/60"
                                    data-gallery-prev>
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button type="button"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/60"
                                    data-gallery-next>
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    {{-- thumbnails --}}
                    @if(count($gallery) > 1)
                        <div class="mt-3 flex gap-2 overflow-x-auto">
                            @foreach($gallery as $index => $url)
                                <button type="button"
                                        data-gallery-thumb="{{ $index }}"
                                        class="border-2 {{ $index === 0 ? 'border-[#9C2F21]' : 'border-transparent' }} rounded-md overflow-hidden w-16 h-12 flex-shrink-0">
                                    <img src="{{ $url }}"
                                         alt="Thumb {{ $index+1 }}"
                                         class="w-full h-full object-cover cursor-pointer">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- RINGKASAN SPESIFIKASI (KOTAK 2x2 / 3) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-[11px] sm:text-xs">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-0 border-b border-gray-200">
                        <div class="px-3 py-2 border-r border-gray-200">
                            <div class="text-gray-500 mb-0.5">Tipe</div>
                            <div class="font-semibold">{{ $perumahan->tipe ?? '-' }}</div>
                        </div>
                        <div class="px-3 py-2 border-r border-gray-200">
                            <div class="text-gray-500 mb-0.5">Tahun Pembangunan</div>
                            <div class="font-semibold">{{ $perumahan->tahun_pembangunan ?? '-' }}</div>
                        </div>
                        <div class="px-3 py-2">
                            <div class="text-gray-500 mb-0.5">Luas Tanah</div>
                            <div class="font-semibold">{{ $perumahan->luas_tanah ?? '-' }} m²</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-0">
                        <div class="px-3 py-2 border-r border-gray-200">
                            <div class="text-gray-500 mb-0.5">Status Unit</div>
                            <div class="font-semibold">
                                {{ $perumahan->status_unit ?? 'Tersedia' }}
                            </div>
                        </div>
                        <div class="px-3 py-2 border-r border-gray-200">
                            <div class="text-gray-500 mb-0.5">Jumlah Unit</div>
                            <div class="font-semibold">{{ $perumahan->jumlah_unit ?? '-' }}</div>
                        </div>
                        <div class="px-3 py-2">
                            <div class="text-gray-500 mb-0.5">Luas Bangunan</div>
                            <div class="font-semibold">{{ $perumahan->luas_bangunan ?? '-' }} m²</div>
                        </div>
                    </div>
                </div>

                {{-- SPESIFIKASI DETAIL --}}
                @php
                    $spesRaw = $perumahan->spesifikasi ?? null;
                    $spesList = [];
                    if ($spesRaw) {
                        $parts = preg_split('/\r\n|\r|\n|,/', $spesRaw);
                        $spesList = array_values(array_filter(array_map('trim', $parts)));
                    }
                @endphp

                @if($spesRaw)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="border-b border-gray-200 px-4 py-2.5 text-sm font-semibold">
                            Spesifikasi
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 text-xs sm:text-sm">
                            @foreach($spesList as $item)
                                <div class="px-4 py-2 border-b border-gray-100">
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- TABEL ANGSURAN (GAMBAR) --}}
                @if(!empty($perumahan->tabel_angsuran_url ?? null))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <h2 class="text-center text-xs sm:text-sm font-semibold mb-3">
                            TABEL PERKIRAAN DAN ANGSURAN RUMAH<br>
                            <span class="text-[#9C2F21] uppercase">{{ $perumahan->nama }}</span>
                        </h2>
                        <div class="overflow-auto">
                            <img src="{{ $perumahan->tabel_angsuran_url }}"
                                 alt="Tabel angsuran {{ $perumahan->nama }}"
                                 class="w-full max-w-full object-contain cursor-zoom-in"
                                 data-zoomable>
                        </div>
                    </div>
                @endif

                {{-- INFORMASI PERUMAHAN LAINNYA (DOKUMEN) --}}
                @php
                    $dokumenUrls = $perumahan->dokumen_foto_urls ?? [];
                @endphp

                @if(!empty($dokumenUrls))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3">
                        <h3 class="text-xs sm:text-sm font-semibold mb-2">
                            Informasi Perumahan Lainnya
                        </h3>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-2">
                            @foreach($dokumenUrls as $url)
                                <div class="border rounded-md overflow-hidden bg-gray-50">
                                    <img src="{{ $url }}"
                                         alt="Dokumen {{ $perumahan->nama }}"
                                         class="w-full h-24 sm:h-28 object-cover cursor-zoom-in"
                                         data-zoomable>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-[11px] text-gray-600">
                            *Foto dokumen dapat mencakup IMB, sertifikat, atau izin lain yang
                            mendukung legalitas perumahan.
                        </p>
                    </div>
                @endif

            </div>

            {{-- ================= KOLOM KANAN ================= --}}
            <aside class="space-y-4">

                {{-- KARTU INFO PERUMAHAN + MAP --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h1 class="text-lg sm:text-xl font-bold">
                        {{ $perumahan->nama }}
                    </h1>

                    @if($perumahan->harga)
                        <p class="mt-1 text-xs sm:text-sm">
                            Mulai <span class="font-bold text-[#9C2F21]">
                                Rp {{ number_format($perumahan->harga, 0, ',', '.') }}
                            </span>
                        </p>
                    @endif

                    <p class="mt-1 text-[11px]">
                        Status unit:
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold
                                     {{ ($perumahan->status_unit ?? 'Tersedia') === 'Terjual'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-green-100 text-green-700' }}">
                            {{ $perumahan->status_unit ?? 'Tersedia' }}
                        </span>
                    </p>

                    @php
                        $desSingkat = $perumahan->deskripsi_singkat
                            ?? ($perumahan->deskripsi
                                ? \Illuminate\Support\Str::limit($perumahan->deskripsi, 120)
                                : null);
                    @endphp

                    @if($desSingkat)
                        <p class="mt-3 text-xs sm:text-sm text-gray-700">
                            {{ $desSingkat }}
                        </p>
                    @endif

                    @if(!empty($perumahan->lokasi_google_map))
                        <div class="mt-4 w-full h-40 rounded-md overflow-hidden bg-gray-100">
                            <iframe
                                src="{{ $perumahan->lokasi_google_map }}"
                                width="100%" height="100%" style="border:0;"
                                allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    @endif

                    <p class="mt-2 text-[11px] text-gray-600">
                        {{ $perumahan->lokasi }}
                    </p>
                </div>

                {{-- KARTU DEVELOPER --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h2 class="text-sm font-semibold mb-1">Developer</h2>
                    <p class="text-xs sm:text-sm mb-3">
                        {{ $perumahan->nama_perusahaan ?? 'Developer tidak diketahui' }}
                    </p>

                    @if($perumahan->telepon)
                        <div class="mb-3 text-xs sm:text-sm">
                            Kontak:
                            <a href="tel:{{ $perumahan->telepon }}"
                               class="font-semibold text-[#9C2F21]">
                                {{ $perumahan->telepon }}
                            </a>
                        </div>
                    @endif

                    @if($perumahan->telepon)
                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $perumahan->telepon) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center w-full px-4 py-2 rounded-md bg-[#009769] hover:bg-[#007e56] text-white text-xs sm:text-sm font-semibold">
                            Hubungi Developer
                        </a>
                    @else
                        <button type="button"
                                class="w-full px-4 py-2 rounded-md bg-gray-300 text-gray-600 text-xs sm:text-sm font-semibold cursor-not-allowed">
                            Kontak Developer Belum Tersedia
                        </button>
                    @endif
                </div>

                {{-- DENAH RUMAH DI KOLOM KANAN --}}
                @if(!empty($perumahan->denah_url ?? null))
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <h3 class="text-xs sm:text-sm font-semibold mb-2 text-center">
                            DENAH RUMAH
                        </h3>
                        <img src="{{ $perumahan->denah_url }}"
                             alt="Denah {{ $perumahan->nama }}"
                             class="w-full h-auto object-contain rounded-md cursor-zoom-in"
                             data-zoomable>
                    </div>
                @endif

                {{-- TOMBOL KEMBALI --}}
                <div class="text-center">
                    <a href="{{ route('perumahan.index') }}"
                       class="inline-block px-4 py-2 text-xs sm:text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                        ← Kembali ke Daftar Perumahan
                    </a>
                </div>

            </aside>
        </div>
    </section>

    {{-- FOOTER --}}
    @include('layouts.masyarakat-footer')

    {{-- OVERLAY ZOOM GAMBAR --}}
    <div id="imgZoomOverlay"
         class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">
        <button id="imgZoomClose"
                class="absolute top-4 right-4 text-white text-2xl font-bold px-2">
            ×
        </button>
        <img id="imgZoomTarget"
             src=""
             class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl border border-white/20"
             alt="Zoom">
    </div>

    {{-- SCRIPT SLIDER + ZOOM --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ===== slider =====
            const container = document.getElementById('perumahanGallery');
            if (container) {
                const slides = container.querySelectorAll('[data-slide]');
                const prevBtn = container.querySelector('[data-gallery-prev]');
                const nextBtn = container.querySelector('[data-gallery-next]');
                const thumbs  = container.querySelectorAll('[data-gallery-thumb]');
                if (slides.length) {
                    let current = 0;
                    function showSlide(index) {
                        if (index < 0) index = slides.length - 1;
                        if (index >= slides.length) index = 0;
                        current = index;
                        slides.forEach((el, i) => {
                            el.classList.toggle('hidden', i !== current);
                        });
                        thumbs.forEach((el, i) => {
                            if (i === current) {
                                el.classList.add('border-[#9C2F21]');
                                el.classList.remove('border-transparent');
                            } else {
                                el.classList.remove('border-[#9C2F21]');
                                el.classList.add('border-transparent');
                            }
                        });
                    }
                    prevBtn && prevBtn.addEventListener('click', () => showSlide(current - 1));
                    nextBtn && nextBtn.addEventListener('click', () => showSlide(current + 1));
                    thumbs.forEach(btn => {
                        btn.addEventListener('click', function () {
                            const idx = parseInt(this.getAttribute('data-gallery-thumb'), 10);
                            showSlide(idx);
                        });
                    });
                }
            }

            // ===== zoom gambar =====
            const overlay = document.getElementById('imgZoomOverlay');
            const zoomImg = document.getElementById('imgZoomTarget');
            const closeBtn = document.getElementById('imgZoomClose');

            function openZoom(src) {
                if (!overlay || !zoomImg) return;
                zoomImg.src = src;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }

            function closeZoom() {
                if (!overlay || !zoomImg) return;
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                zoomImg.src = '';
            }

            document.querySelectorAll('[data-zoomable]').forEach(img => {
                img.addEventListener('click', () => openZoom(img.src));
            });

            if (overlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) {
                        closeZoom();
                    }
                });
            }
            closeBtn && closeBtn.addEventListener('click', closeZoom);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeZoom();
                }
            });
        });
    </script>
</body>
</html>
