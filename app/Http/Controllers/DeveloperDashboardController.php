<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perumahan;
use App\Models\PermohonanNotaDinas;

class DeveloperDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        // ====== KPI / KARTU ATAS ======
        $totalPerumahan = Perumahan::where('user_id', $userId)->count();

        $totalPermohonan = PermohonanNotaDinas::where('user_id', $userId)->count();

        // misalnya "Perlu Revisi" = permohonan nota dinas yg status-nya 'revisi'
        $totalPerluRevisi = PermohonanNotaDinas::where('user_id', $userId)
            ->where('status', 'revisi')
            ->count();

        // ====== AKTIVITAS TERBARU (gabungan Perumahan + Permohonan Nota Dinas) ======

        // Aktivitas dari PERUMAHAN (dibatasi 10 dulu)
        $perumahanActivities = Perumahan::where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->take(10)
            ->get()
            ->map(function (Perumahan $p) {
                // bikin teks status yang enak dibaca
                $status = $p->status ?? 'pending';

                if ($status === 'disetujui') {
                    $keterangan = 'Perumahan "' . $p->nama . '" disetujui oleh Dinas.';
                } elseif ($status === 'revisi') {
                    $keterangan = 'Perumahan "' . $p->nama . '" memerlukan revisi sesuai catatan Dinas.';
                } elseif ($status === 'ditolak') {
                    $keterangan = 'Perumahan "' . $p->nama . '" ditolak oleh Dinas.';
                } else {
                    // pending / null → dianggap pengajuan / update
                    $keterangan = 'Perumahan "' . $p->nama . '" diajukan / diperbarui dan menunggu verifikasi.';
                }

                return [
                    'tanggal'    => $p->updated_at,
                    'keterangan' => $keterangan,
                    'status'     => $status,
                    'jenis'      => 'perumahan',
                ];
            });

        // Aktivitas dari PERMOHONAN NOTA DINAS
        $permohonanActivities = PermohonanNotaDinas::where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->take(10)
            ->get()
            ->map(function (PermohonanNotaDinas $perm) {
                $status = $perm->status ?? 'pending';

                if ($status === 'disetujui') {
                    $keterangan = 'Permohonan Nota Dinas untuk "' . $perm->nama_perumahan . '" disetujui Dinas.';
                } elseif ($status === 'revisi') {
                    $keterangan = 'Permohonan Nota Dinas untuk "' . $perm->nama_perumahan . '" memerlukan revisi.';
                } elseif ($status === 'ditolak') {
                    $keterangan = 'Permohonan Nota Dinas untuk "' . $perm->nama_perumahan . '" ditolak Dinas.';
                } else {
                    $keterangan = 'Permohonan Nota Dinas untuk "' . $perm->nama_perumahan . '" dikirim dan menunggu verifikasi.';
                }

                return [
                    'tanggal'    => $perm->updated_at,
                    'keterangan' => $keterangan,
                    'status'     => $status,
                    'jenis'      => 'permohonan',
                ];
            });

        // Gabung dua sumber, urutkan berdasarkan tanggal terbaru, ambil 5 teratas
        $activities = $perumahanActivities
            ->merge($permohonanActivities)
            ->sortByDesc('tanggal')
            ->take(5)
            ->values();

        return view('developer.dashboard', [
            'totalPerumahan'   => $totalPerumahan,
            'totalPermohonan'  => $totalPermohonan,
            'totalPerluRevisi' => $totalPerluRevisi,
            'activities'       => $activities,
        ]);
    }
}
