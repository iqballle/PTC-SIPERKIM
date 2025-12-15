{{-- resources/views/layouts/masyarakat-navbar.blade.php --}}
@php
    // Deteksi halaman aktif berdasarkan nama route
    $isHome      = request()->routeIs('home', 'masyarakat.dashboard');
    $isPerumahan = request()->routeIs('perumahan.index', 'masyarakat.cari', 'perumahan.show');
    $isAbout     = request()->routeIs('about');
@endphp

<nav class="bg-[#9C2F21] text-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center relative">
        {{-- Logo dan nama (kiri) --}}
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-siperkim.png') }}" alt="Logo SIPERKIM" class="w-12 h-12 rounded-full object-cover bg-white">
            <div>
                <div class="text-lg font-extrabold leading-tight">SIPERKIM</div>
                <div class="text-xs opacity-80">Kota Parepare</div>
            </div>
        </div>

        {{-- Menu utama (ditaruh di tengah dengan flex-1 + justify-center) --}}
        <div class="flex-1 flex justify-center">
            <div class="hidden md:flex space-x-8 text-sm font-semibold items-center">
                {{-- Beranda --}}
                <a href="{{ route('home') }}"
                   class="pb-1 {{ $isHome ? 'border-b-2 border-white' : 'hover:border-b-2 hover:border-white' }}">
                    BERANDA
                </a>

                {{-- Daftar Perumahan --}}
                <a href="{{ route('perumahan.index') }}"
                   class="pb-1 {{ $isPerumahan ? 'border-b-2 border-white' : 'hover:border-b-2 hover:border-white' }}">
                    DAFTAR PERUMAHAN
                </a>

                {{-- Tentang Kami --}}
                <a href="{{ route('about') }}"
                   class="pb-1 {{ $isAbout ? 'border-b-2 border-white' : 'hover:border-b-2 hover:border-white' }}">
                    TENTANG KAMI
                </a>

                {{-- HUBUNGI KAMI (popup) --}}
                <button id="contactMenuButton" type="button"
                        class="relative flex items-center gap-1 pb-1 hover:border-b-2 hover:border-white focus:outline-none">
                    HUBUNGI KAMI
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- POPUP HUBUNGI KAMI (masih di-absolute ke kanan nav) --}}
        <div id="contactMenu"
             class="hidden absolute right-4 top-full mt-3 bg-white text-gray-900 rounded-2xl shadow-xl w-72 z-50">
            <div class="p-4 space-y-4 text-sm">

                {{-- Email --}}
                <a href="mailto:TimTumbuhithpare@gmail.com"
                   class="flex items-start gap-3 hover:bg-gray-50 rounded-lg p-2">
                    <div class="mt-1">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 8l9 6 9-6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Email</div>
                        <div class="text-xs leading-snug">
                            TimTumbuhithpare<br>@gmail.com
                        </div>
                    </div>
                </a>

                {{-- Phone --}}
                <a href="tel:08512312313"
                   class="flex items-start gap-3 hover:bg-gray-50 rounded-lg p-2">
                    <div class="mt-1">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 5a2 2 0 012-2h1.5a1 1 0 01.95.684l1.2 3.593a1 1 0 01-.27 1.05l-1.22 1.22a1 1 0 000 1.414l3.586 3.586a1 1 0 001.414 0l1.22-1.22a1 1 0 011.05-.27l3.593 1.2A1 1 0 0121 16.5V18a2 2 0 01-2 2h-1C9.82 20 4 14.18 4 7V5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Phone</div>
                        <div class="text-xs leading-snug">
                            08512312313
                        </div>
                    </div>
                </a>

                {{-- Address --}}
                <a href="https://www.google.com/maps/search/?api=1&query=BTN+Sunrise+City+Blok+K1+Parepare"
                   target="_blank" rel="noopener"
                   class="flex items-start gap-3 hover:bg-gray-50 rounded-lg p-2">
                    <div class="mt-1">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 11a3 3 0 100-6 3 3 0 000 6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M5.5 10.5C5.5 6.91 8.14 4 12 4s6.5 2.91 6.5 6.5S14.5 19 12 20.5 5.5 14.09 5.5 10.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-sm">Address</div>
                        <div class="text-xs leading-snug">
                            BTN Sunrise City Blok K1<br>Parepare
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</nav>

{{-- Script toggle popup --}}
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