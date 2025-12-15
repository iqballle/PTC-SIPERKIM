<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use Illuminate\Http\Request;

class DinasVerifikasiController extends Controller
{
    /**
     * Halaman daftar perumahan untuk diverifikasi.
     * Filter ?status=pending|disetujui|revisi|inventaris (default: pending)
     *
     * inventaris = semua perumahan disetujui + filter/search
     */
    public function index(Request $request)
    {
        // Wajib login dengan role dinas
        if (!auth()->check() || auth()->user()->role !== 'dinas') {
            abort(403);
        }

        $status  = $request->query('status', 'pending');
        $allowed = ['pending', 'disetujui', 'revisi', 'inventaris'];

        if (!in_array($status, $allowed, true)) {
            $status = 'pending';
        }

        // Query dasar
        $query = Perumahan::with(['developer']);

        // Filter status (core logic)
        if ($status === 'revisi') {
            // Perlu Revisi = pending + ada catatan revisi
            $query->where('status', 'pending')
                  ->whereNotNull('catatan_revisi');

        } elseif ($status === 'pending') {
            // Pending murni = pending + TIDAK ada catatan revisi
            $query->where('status', 'pending')
                  ->whereNull('catatan_revisi');

        } elseif ($status === 'disetujui') {
            // Disetujui
            $query->where('status', 'disetujui');

        } else {
            // ✅ inventaris = disetujui (arsip)
            $query->where('status', 'disetujui');

            /**
             * ✅ FILTER INVENTARIS (opsional)
             * - q    : cari nama/lokasi/nama_perusahaan/nama developer
             * - year : filter tahun approved_at
             */

            // Search
            if ($request->filled('q')) {
                $q = trim($request->q);

                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('lokasi', 'like', "%{$q}%")
                      ->orWhere('nama_perusahaan', 'like', "%{$q}%")
                      ->orWhereHas('developer', function ($dev) use ($q) {
                          $dev->where('name', 'like', "%{$q}%");
                      });
                });
            }

            // Filter tahun (approved_at)
            if ($request->filled('year')) {
                $year = (int) $request->year;
                if ($year > 0) {
                    $query->whereYear('approved_at', $year);
                }
            }
        }

        // lebih rapi kalau inventaris urut berdasarkan approved_at terbaru
        if ($status === 'inventaris') {
            $query->orderByDesc('approved_at');
        } else {
            $query->latest(); // default created_at desc
        }

        $items = $query->paginate(10)->appends($request->query());

        // Hitung jumlah untuk tab
        $counts = [
            'pending'    => Perumahan::where('status', 'pending')
                                     ->whereNull('catatan_revisi')
                                     ->count(),

            'disetujui'  => Perumahan::where('status', 'disetujui')->count(),

            'revisi'     => Perumahan::where('status', 'pending')
                                     ->whereNotNull('catatan_revisi')
                                     ->count(),

            // ✅ inventaris = disetujui (total arsip)
            'inventaris' => Perumahan::where('status', 'disetujui')->count(),
        ];

        return view('dinas.perumahan.index', compact('items', 'status', 'counts'));
    }

    /**
     * DETAIL 1 perumahan (untuk dinas memeriksa sebelum memutuskan)
     * Route: dinas.perumahan.verify.show
     */
    public function show($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'dinas') {
            abort(403);
        }

        $perumahan = Perumahan::with(['developer', 'approver'])->findOrFail($id);

        return view('dinas.perumahan.show', compact('perumahan'));
    }

    /**
     * Setujui perumahan (pindah ke status 'disetujui')
     * Route: dinas.perumahan.approve (POST)
     */
    public function approve($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'dinas') {
            abort(403);
        }

        $p = Perumahan::findOrFail($id);

        $p->status = 'disetujui';

        // kosongkan catatan revisi jika ada
        if ($p->isFillable('catatan_revisi')) {
            $p->catatan_revisi = null;
        }

        if ($p->isFillable('approved_at')) {
            $p->approved_at = now();
        }

        if ($p->isFillable('approved_by')) {
            $p->approved_by = auth()->id();
        }

        $p->save();

        return redirect()
            ->route('dinas.perumahan.verify.index', ['status' => 'pending'])
            ->with('status', 'Perumahan berhasil DISETUJUI.');
    }

    /**
     * Tandai perumahan PERLU REVISI
     * Route: dinas.perumahan.reject (POST)
     */
    public function reject(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'dinas') {
            abort(403);
        }

        $request->validate([
            'catatan_revisi' => ['nullable', 'string'],
        ]);

        $p = Perumahan::findOrFail($id);

        // Revisi = status tetap pending, tapi ada catatan_revisi
        $p->status = 'pending';

        if ($p->isFillable('catatan_revisi')) {
            $p->catatan_revisi = $request->filled('catatan_revisi')
                ? $request->catatan_revisi
                : null;
        }

        // reset approval
        if ($p->isFillable('approved_at')) {
            $p->approved_at = null;
        }

        if ($p->isFillable('approved_by')) {
            $p->approved_by = null;
        }

        $p->save();

        return redirect()
            ->route('dinas.perumahan.verify.index', ['status' => 'pending'])
            ->with('status', 'Perumahan ditandai PERLU REVISI.');
    }
}