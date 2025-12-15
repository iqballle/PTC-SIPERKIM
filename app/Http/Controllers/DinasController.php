<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // Ringkasan perumahan
        $totalPerumahan      = Perumahan::count();
        $perumahanDisetujui  = Perumahan::where('status', 'disetujui')->count();
        $perumahanPending    = Perumahan::where('status', 'pending')->count();

        // Ringkasan permohonan nota dinas
        $notaPending   = PermohonanNotaDinas::where('status', 'pending')->count();
        $notaDisetujui = PermohonanNotaDinas::where('status', 'disetujui')->count();
        $notaRevisi    = PermohonanNotaDinas::where('status', 'revisi')->count();

        // 5 permohonan terbaru
        $recentNota = PermohonanNotaDinas::with('perumahan', 'user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dinas.dashboard', compact(
            'totalPerumahan',
            'perumahanDisetujui',
            'perumahanPending',
            'notaPending',
            'notaDisetujui',
            'notaRevisi',
            'recentNota'
        ));
    }
}
