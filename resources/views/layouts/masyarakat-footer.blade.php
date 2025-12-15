{{-- resources/views/layouts/masyarakat-footer.blade.php --}}
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