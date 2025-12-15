<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermohonanNotaDinas;
use App\Models\Perumahan;

class DinasPermohonanController extends Controller
{
    public function __construct()
    {
        // semua route di controller ini wajib login
        $this->middleware('auth');
    }

    /**
     * Helper: pastikan user adalah dinas
     */
    protected function ensureDinas()
    {
        if (!auth()->check() || auth()->user()->role !== 'dinas') {
            abort(403);
        }
    }

    /**
     * LIST permohonan nota dinas (permohonan masuk)
     * Route: dinas.permohonan.nota.index
     */
    public function index(Request $request)
    {
        $this->ensureDinas();

        $status       = $request->input('status');
        $perumahanId  = $request->input('perumahan_id'); // ketika klik dari inventaris

        $query = PermohonanNotaDinas::query()
            ->with(['perumahan', 'user'])
            ->orderByDesc('created_at');

        // filter status (optional)
        if ($status) {
            $query->where('status', $status);
        }

        // filter per perumahan (jika datang dari inventaris)
        $perumahan = null;
        if ($perumahanId) {
            $query->where('perumahan_id', $perumahanId);
            $perumahan = Perumahan::find($perumahanId);
        }

        $permohonans = $query->paginate(15)->withQueryString();

        return view('dinas.permohonan-nota-index', compact('permohonans', 'status', 'perumahan'));
    }

    /**
     * DETAIL 1 permohonan
     * Route: dinas.permohonan.nota.show
     */
    public function show($id)
    {
        $this->ensureDinas();

        $permohonan = PermohonanNotaDinas::with(['perumahan', 'user'])
            ->findOrFail($id);

        return view('dinas.permohonan-nota-show', compact('permohonan'));
    }

    /**
     * SETUJUI permohonan
     * Route: dinas.permohonan.nota.approve (POST)
     */
    public function approve($id, Request $request)
    {
        $this->ensureDinas();

        $permohonan = PermohonanNotaDinas::findOrFail($id);

        $permohonan->status        = 'disetujui';
        $permohonan->catatan_dinas = $request->input('catatan_dinas'); // boleh kosong
        $permohonan->verified_at   = now();
        $permohonan->verified_by   = auth()->user()->name ?? 'Petugas Dinas';

        $permohonan->save();

        return redirect()
            ->route('dinas.permohonan.nota.index')
            ->with('status', 'Permohonan Nota Dinas telah DISETUJUI.');
    }

    /**
     * TANDAI PERLU REVISI
     * Route: dinas.permohonan.nota.revisi (POST)
     */
    public function revisi($id, Request $request)
    {
        $this->ensureDinas();

        $data = $request->validate([
            'catatan_dinas' => ['required', 'string', 'max:1000'],
        ]);

        $permohonan = PermohonanNotaDinas::findOrFail($id);

        $permohonan->status        = 'revisi';
        $permohonan->catatan_dinas = $data['catatan_dinas'];
        $permohonan->verified_at   = now();
        $permohonan->verified_by   = auth()->user()->name ?? 'Petugas Dinas';

        $permohonan->save();

        return redirect()
            ->route('dinas.permohonan.nota.index')
            ->with('status', 'Permohonan diberi status PERLU REVISI.');
    }

    /**
     * INVENTARIS Nota Dinas per PERUMAHAN
     * Route: dinas.permohonan.nota.inventaris (GET)
     *
     * Menampilkan 1 baris per perumahan:
     * - total permohonan
     * - jumlah pending / disetujui / revisi / ditolak
     * - tanggal pengajuan terakhir
     */
    public function inventaris(Request $request)
    {
        $this->ensureDinas();

        $builder = PermohonanNotaDinas::query();

        // filter keyword (nama perumahan / pengembang)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $builder->where(function ($sub) use ($q) {
                $sub->where('nama_perumahan', 'like', "%{$q}%")
                    ->orWhere('nama_pengembang', 'like', "%{$q}%");
            });
        }

        // filter tahun berdasarkan created_at (tahun pengajuan)
        if ($request->filled('tahun')) {
            $builder->whereYear('created_at', $request->tahun);
        }

        $items = $builder
            ->select(
                'perumahan_id',
                'nama_perumahan',
                'nama_pengembang',
                DB::raw('COUNT(*) as total_permohonan'),
                DB::raw("SUM(CASE WHEN status = 'pending'   THEN 1 ELSE 0 END) as jml_pending"),
                DB::raw("SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as jml_disetujui"),
                DB::raw("SUM(CASE WHEN status = 'revisi'    THEN 1 ELSE 0 END) as jml_revisi"),
                DB::raw("SUM(CASE WHEN status = 'ditolak'   THEN 1 ELSE 0 END) as jml_ditolak"),
                DB::raw('MAX(created_at) as terakhir_diajukan')
            )
            ->groupBy('perumahan_id', 'nama_perumahan', 'nama_pengembang')
            ->orderBy('nama_perumahan')
            ->get();

        return view('dinas.permohonan-nota-inventaris', compact('items'));
    }
}