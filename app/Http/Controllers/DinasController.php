<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use App\Models\PermohonanNotaDinas;

class DinasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ============================================
        // ✅ RINGKASAN PERUMAHAN (pakai 2 kolom status)
        // ============================================
        $totalPerumahan = Perumahan::count();

        $perumahanDisetujui = Perumahan::query()
            ->where(function ($q) {
                $q->whereIn('status', ['disetujui', 'approved'])
                  ->orWhereIn('status_verifikasi', ['disetujui', 'approved']);
            })
            ->count();

        $perumahanPending = Perumahan::query()
            ->where(function ($q) {
                $q->whereIn('status', ['pending'])
                  ->orWhereIn('status_verifikasi', ['pending']);
            })
            ->count();

        // ============================================
        // ✅ RINGKASAN PERMOHONAN NOTA DINAS
        // ============================================
        $notaPending   = PermohonanNotaDinas::where('status', 'pending')->count();
        $notaDisetujui = PermohonanNotaDinas::where('status', 'disetujui')->count();
        $notaRevisi    = PermohonanNotaDinas::where('status', 'revisi')->count();

        // ============================================
        // ✅ 5 permohonan nota terbaru
        // ============================================
        $recentNota = PermohonanNotaDinas::with('perumahan', 'user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ============================================
        // ✅ 10 perumahan disetujui terbaru (buat tabel di dashboard dinas)
        // ============================================
        $recentPerumahanApproved = Perumahan::query()
            ->with('developer')
            ->where(function ($q) {
                $q->whereIn('status', ['disetujui', 'approved'])
                  ->orWhereIn('status_verifikasi', ['disetujui', 'approved']);
            })
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        return view('dinas.dashboard', compact(
            'totalPerumahan',
            'perumahanDisetujui',
            'perumahanPending',
            'notaPending',
            'notaDisetujui',
            'notaRevisi',
            'recentNota',
            'recentPerumahanApproved'
        ));
    }
}