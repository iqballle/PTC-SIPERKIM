<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami — SIPERKIM</title>

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


    {{-- NAVBAR --}}
    @include('layouts.masyarakat-navbar', ['active' => 'home'])

    {{-- HEADER HIJAU --}}
    <section class="bg-[#7ED6C4]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 flex flex-col md:flex-row items-center gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-siperkim.png') }}" class="w-20 h-20" alt="SIPERKIM">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold">
                        Sistem Informasi Perumahan Kota Parepare
                    </h1>
                    <p class="text-sm sm:text-base font-medium">
                        dan Monitoring RTH berbasis IoT
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTEN TENTANG KAMI --}}
    <main class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6 lg:p-8">

                {{-- Baris atas: Visi & Misi + Foto kantor --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    {{-- Visi & Misi --}}
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Visi &amp; Misi</h2>
                        <p class="text-sm italic mb-3">
                            “Terwujudnya Perumahan dan Permukiman yang Berwawasan Lingkungan
                            serta Tanah Aset Pemda yang Akuntabel”
                        </p>
                        <ol class="list-decimal list-inside text-sm space-y-1">
                            <li>Meningkatkan kapasitas organisasi serta peningkatan profesionalisme sumber daya aparatur.</li>
                            <li>Meningkatkan perencanaan dan pelaksanaan pembangunan perumahan kawasan permukiman.</li>
                            <li>Mewujudkan kawasan perumahan dan permukiman yang lebih layak dan berwawasan lingkungan.</li>
                            <li>Meningkatkan pengelolaan dan pengamanan tanah aset pemerintah yang akuntabel.</li>
                            <li>Mewujudkan tertib administrasi kepemilikan dan penguasaan tanah serta penyelesaian sengketa tanah yang adil guna kesejahteraan masyarakat.</li>
                        </ol>
                    </div>

                    {{-- Foto Kantor Dinas --}}
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/tentang-kami-kantor.jpg') }}"
                             alt="Kantor Dinas Perumahan"
                             class="rounded-md shadow-md w-full max-h-64 object-cover mb-2">
                        <p class="text-xs text-gray-700 text-center">
                            Kantor Dinas Perumahan, Kawasan Permukiman dan Pertanahan Kota Parepare
                        </p>
                    </div>
                </div>

                {{-- Baris kedua: Struktur + Tim (kiri), Tujuan + Lokasi (kanan) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- KOLUMN KIRI: STRUKTUR + TIM --}}
                    <div class="space-y-4">
                        {{-- Struktur Organisasi --}}
                        <div class="flex flex-col items-center">
                            <h3 class="text-sm font-semibold mb-2">STRUKTUR ORGANISASI</h3>
                            <img src="{{ asset('images/struktur-organisasi-siperkim.png') }}"
                                 alt="Struktur Organisasi"
                                 class="rounded-md shadow-md w-full object-contain">
                        </div>

                        {{-- Tim Pengembang / Mitra --}}
                        <div>
                            <h3 class="text-sm font-semibold mb-3">TIM PENGEMBANG / MITRA</h3>
                            <div class="flex items-center gap-3 mb-2">
                                <img src="{{ asset('images/logo-ith.png') }}" alt="ITH"
                                     class="w-16 h-16 object-contain">
                                <div class="text-sm">
                                    <div class="font-semibold">Mahasiswa Institut Teknologi BJ Habibie</div>
                                    <div>Kelompok TUMBUH</div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-700 mt-1">
                                SIPERKIM dikembangkan sebagai kolaborasi antara Dinas Perumahan, Kawasan Permukiman
                                dan Pertanahan Kota Parepare dengan mahasiswa ITH, untuk mendukung tata kelola data
                                perumahan yang lebih modern dan partisipatif.
                            </p>
                        </div>
                    </div>

                    {{-- KOLUMN KANAN: TUJUAN + LOKASI (MAP) --}}
                    <div class="flex flex-col gap-4">
                        {{-- Tujuan SIPERKIM --}}
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Tujuan SIPERKIM</h2>
                            <ol class="list-decimal list-inside text-sm space-y-1">
                                <li>Digitalisasi data perumahan dan kawasan permukiman.</li>
                                <li>Meningkatkan transparansi dan akuntabilitas informasi perumahan bagi masyarakat.</li>
                                <li>Mempermudah koordinasi antara Developer dan Dinas PERKIMTAN.</li>
                                <li>Mendukung program Smart City Kota Parepare melalui sistem monitoring RTH berbasis IoT.</li>
                            </ol>
                        </div>

                        {{-- Lokasi Dinas PERKIMTAN (Google Maps) --}}
                        <div class="flex flex-col gap-2">
                            <h3 class="text-sm font-semibold">Lokasi Dinas PERKIMTAN Kota Parepare</h3>

                            <div class="w-full rounded-md shadow-md overflow-hidden" style="height: 230px;">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3980.048351953608!2d119.62543710000001!3d-4.0104796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d95bb2214446339%3A0xff264f471e80853d!2sDinas%20Perumahan%2C%20Kawasan%20Permukiman%20dan%20Pertanahan%20(DPKPP)!5e0!3m2!1sid!2sid!4v1763173405804!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                    width="100%"
                                    height="150%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>

                            <p class="text-xs text-gray-700 text-center">
                                Dinas Perumahan, Kawasan Permukiman dan Pertanahan Kota Parepare
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-[#9C2F21] text-white mt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
            {{-- Kolom kiri --}}
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

            {{-- Navigasi --}}
            <div>
                <h3 class="font-semibold mb-2 text-sm">NAVIGASI</h3>
                <ul class="space-y-1 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:underline">Beranda</a></li>
                    <li><a href="{{ route('perumahan.index') }}" class="hover:underline">Daftar Perumahan</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline">Tentang Kami</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h3 class="font-semibold mb-2 text-sm">Kontak Kami</h3>
                <p class="text-xs">
                    BTN Sunrise Blok k1<br>
                    0812423456<br>
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

</body>
</html>