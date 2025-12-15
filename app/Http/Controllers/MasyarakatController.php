<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use Illuminate\Http\Request;

class MasyarakatController extends Controller
{
    /**
     * DASHBOARD MASYARAKAT (BERANDA)
     * Menampilkan perumahan disetujui (bisa juga difilter jika dipanggil dengan query string)
     */
    public function showDashboard(Request $request)
    {
        $perumahans = $this->buildApprovedQuery($request)
            ->latest()
            ->paginate(12)
            ->withQueryString(); // biar parameter filter tetap ada saat pindah halaman

        return view('masyarakat.dashboard', compact('perumahans'));
    }

    /**
     * DAFTAR PERUMAHAN (MENU NAVBAR)
     * Route: perumahan.index
     *
     * - HANYA menampilkan perumahan yang status-nya "disetujui"
     * - Menerima filter dari:
     *   • form di dashboard (nama, lokasi, tipe, status unit, harga_min, harga_max, fasilitas)
     *   • kotak pencarian "q" di halaman daftar perumahan
     */
    public function listPerumahan(Request $request)
    {
        // pakai helper yang sama dengan dashboard (nama, lokasi, tipe, status unit, fasilitas, harga min–max)
        $q = $this->buildApprovedQuery($request);

        // tambahan: kalau ada "q" (pencarian bebas di daftar perumahan)
        if ($request->filled('q')) {
            $keyword = trim($request->q);

            $q->where(function ($sub) use ($keyword) {
                $sub->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('lokasi', 'like', "%{$keyword}%")
                    ->orWhere('nama_perusahaan', 'like', "%{$keyword}%")
                    ->orWhere('tipe', 'like', "%{$keyword}%");
            });
        }

        $perumahans = $q->latest()
            ->paginate(12)
            ->withQueryString();

        // view khusus daftar perumahan (tampilan seperti halaman “Daftar Perumahan”)
        return view('masyarakat.perumahan-index', compact('perumahans'));
    }

    /**
     * Endpoint pencarian lama.
     * Biar tidak bingung, sekarang cukup REDIRECT ke daftar perumahan
     * sambil membawa semua parameter query (nama, lokasi, dst).
     *
     * Route: masyarakat.cari
     */
    public function cariPerumahan(Request $request)
    {
        return redirect()->route('perumahan.index', $request->query());
    }

    /**
     * DETAIL PERUMAHAN UNTUK MASYARAKAT
     * Route: perumahan.show
     */
    public function showPerumahan($id)
    {
        // hanya ambil perumahan yang sudah disetujui
        $perumahan = Perumahan::where('status', 'disetujui')
            ->findOrFail($id);

        return view('masyarakat.perumahan-detail', compact('perumahan'));
    }

    /**
     * Helper: menyusun query list yang sudah DISETUJUI + filter lengkap (dashboard)
     * Dipakai oleh:
     *  - showDashboard
     *  - listPerumahan
     */
    private function buildApprovedQuery(Request $request)
    {
        // basis: hanya perumahan yang SUDAH DISETUJUI oleh dinas
        $q = Perumahan::where('status', 'disetujui');

        // Nama perumahan
        if ($request->filled('nama')) {
            $q->where('nama', 'like', '%' . trim($request->nama) . '%');
        }

        // Lokasi (cari di kolom 'lokasi')
        if ($request->filled('lokasi')) {
            $q->where('lokasi', 'like', '%' . trim($request->lokasi) . '%');
        }

        // Tipe rumah
        if ($request->filled('tipe')) {
            $q->where('tipe', 'like', '%' . trim($request->tipe) . '%');
        }

        // Status UNIT (Tersedia / Terjual) — dari form dashboard (name="status")
        if ($request->filled('status')) {
            $q->where('status_unit', $request->status);
        }

        // Fasilitas (cari teks bebas)
        if ($request->filled('fasilitas')) {
            $q->where('fasilitas', 'like', '%' . trim($request->fasilitas) . '%');
        }

        // Rentang Harga (membersihkan "Rp", titik, koma, spasi)
        [$min, $max] = $this->cleanPriceRange(
            $request->input('harga_min'),
            $request->input('harga_max')
        );

        if (!is_null($min) || !is_null($max)) {
            if (!is_null($min) && !is_null($max)) {
                // kalau user kebalik isi min & max, tukar
                if ($min > $max) {
                    [$min, $max] = [$max, $min];
                }
                $q->whereBetween('harga', [$min, $max]);
            } elseif (!is_null($min)) {
                $q->where('harga', '>=', $min);
            } else {
                $q->where('harga', '<=', $max);
            }
        }

        return $q;
    }

    /**
     * Membersihkan input harga menjadi integer; kembalikan [min|null, max|null]
     */
    private function cleanPriceRange($min, $max): array
    {
        $toInt = function ($v) {
            if ($v === null || $v === '') return null;
            // hapus semua karakter non-angka (Rp, ., ,, spasi, dll)
            $num = preg_replace('/\D+/', '', (string) $v);
            return $num === '' ? null : (int) $num;
        };

        return [$toInt($min), $toInt($max)];
    }
}
