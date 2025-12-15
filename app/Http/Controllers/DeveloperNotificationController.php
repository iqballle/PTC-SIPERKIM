<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use App\Models\PermohonanNotaDinas;

class DeveloperNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman Notifikasi & Revisi untuk Developer
     */
    public function index()
    {
        $userId = auth()->id();

        // 1) Perumahan perlu revisi: pending + ada catatan_revisi
        $perumahanRevisi = Perumahan::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereNotNull('catatan_revisi')
            ->latest()
            ->get();

        // 2) Nota dinas perlu revisi
        $notaRevisi = PermohonanNotaDinas::where('user_id', $userId)
            ->where('status', 'revisi')
            ->orderByDesc('created_at')
            ->get();

        // 3) Nota dinas disetujui (opsional)
        $notaDisetujui = PermohonanNotaDinas::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->orderByDesc('verified_at')
            ->take(10)
            ->get();

        // ✅ 4) Perumahan disetujui (baru)
        $perumahanDisetujui = Perumahan::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->orderByDesc('approved_at')
            ->take(10)
            ->get();

        return view('developer.notifikasi.index', compact(
            'perumahanRevisi',
            'notaRevisi',
            'notaDisetujui',
            'perumahanDisetujui'
        ));
    }
}