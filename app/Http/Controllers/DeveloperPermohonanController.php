<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use App\Models\PermohonanNotaDinas;
use Illuminate\Http\Request;

class DeveloperPermohonanController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user login (developer) yang bisa akses
        $this->middleware('auth');
    }

    /**
     * Halaman utama "Permohonan ke Dinas"
     */
    public function index()
    {
        $notaDinasList = PermohonanNotaDinas::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('developer.permohonan.index', compact('notaDinasList'));
    }

    /**
     * Form permohonan Nota Dinas Pembangunan Perumahan (create baru)
     */
    public function createNotaDinas()
    {
        // hanya perumahan milik developer yang login
        $perumahans = Perumahan::where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        return view('developer.permohonan.nota-dinas-create', compact('perumahans'));
    }

    /**
     * Simpan permohonan Nota Dinas (create baru)
     */
    public function storeNotaDinas(Request $request)
    {
        // 1. VALIDASI INPUT TEKS
        $data = $request->validate([
            'perumahan_id'        => ['required', 'exists:perumahans,id'],
            'nama_pengembang'     => ['required', 'string', 'max:150'],
            'nama_perumahan'      => ['required', 'string', 'max:150'],
            'telepon'             => ['nullable', 'string', 'max:30'],
            'alamat_perumahan'    => ['required', 'string', 'max:255'],
            'kelurahan'           => ['required', 'string', 'max:100'],
            'kecamatan'           => ['required', 'string', 'max:100'],
            'keterangan_tambahan' => ['nullable', 'string'],
            // file dokumen boleh kosong, ditangani di handleFileUploads()
        ]);

        // 2. BUAT RECORD UTAMA TANPA FILE DULU
        $permohonan = PermohonanNotaDinas::create([
            'user_id'             => auth()->id(),
            'perumahan_id'        => $data['perumahan_id'],
            'nama_pengembang'     => $data['nama_pengembang'],
            'nama_perumahan'      => $data['nama_perumahan'],
            'telepon'             => $data['telepon'] ?? null,
            'alamat_perumahan'    => $data['alamat_perumahan'],
            'kelurahan'           => $data['kelurahan'],
            'kecamatan'           => $data['kecamatan'],
            'keterangan_tambahan' => $data['keterangan_tambahan'] ?? null,
            'status'              => 'pending',
        ]);

        // 3. SIMPAN FILE + UPDATE FLAG dok_* JIKA ADA
        $this->handleFileUploads($request, $permohonan);

        return redirect()
            ->route('developer.permohonan.index')
            ->with('status', 'Permohonan Nota Dinas berhasil dikirim ke Dinas untuk diverifikasi.');
    }

    /**
     * DETAIL satu permohonan (tombol "Detail")
     */
    public function showNotaDinas($id)
    {
        $permohonan = PermohonanNotaDinas::where('id', $id)
            ->where('user_id', auth()->id())   // hanya milik developer ini
            ->firstOrFail();

        return view('developer.permohonan.nota-dinas-show', compact('permohonan'));
    }

    /**
     * FORM EDIT untuk permohonan yang direvisi (tombol "Perbaiki")
     */
    public function editNotaDinas($id)
    {
        $permohonan = PermohonanNotaDinas::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // ambil perumahan milik developer untuk dropdown (kalau nanti mau dipakai)
        $perumahans = Perumahan::where('user_id', auth()->id())
            ->orderBy('nama')
            ->get();

        return view('developer.permohonan.nota-dinas-edit', compact('permohonan', 'perumahans'));
    }

    /**
     * UPDATE permohonan Nota Dinas (revisi dari developer)
     */
    public function updateNotaDinas(Request $request, $id)
    {
        $permohonan = PermohonanNotaDinas::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // VALIDASI (TANPA nama_perumahan karena tidak dikirim dari form edit)
        $data = $request->validate([
            'perumahan_id'        => ['required', 'exists:perumahans,id'],
            'nama_pengembang'     => ['required', 'string', 'max:150'],
            'telepon'             => ['nullable', 'string', 'max:30'],
            'alamat_perumahan'    => ['required', 'string', 'max:255'],
            'kelurahan'           => ['required', 'string', 'max:100'],
            'kecamatan'           => ['required', 'string', 'max:100'],
            'keterangan_tambahan' => ['nullable', 'string'],
        ]);

        // UPDATE FIELD TEKS (nama_perumahan dibiarkan nilai lamanya)
        $permohonan->update([
            'perumahan_id'        => $data['perumahan_id'],
            'nama_pengembang'     => $data['nama_pengembang'],
            'telepon'             => $data['telepon'] ?? null,
            'alamat_perumahan'    => $data['alamat_perumahan'],
            'kelurahan'           => $data['kelurahan'],
            'kecamatan'           => $data['kecamatan'],
            'keterangan_tambahan' => $data['keterangan_tambahan'] ?? null,
            // kalau sebelumnya status "revisi", kembalikan ke "pending" saat kirim ulang
            'status'              => $permohonan->status === 'revisi'
                                        ? 'pending'
                                        : $permohonan->status,
        ]);

        // HANDLE FILE (hanya yang diupload di form edit)
        $this->handleFileUploads($request, $permohonan);

        return redirect()
            ->route('developer.permohonan.nota.show', $permohonan->id)
            ->with('status', 'Perbaikan permohonan berhasil disimpan dan dikirim ulang ke Dinas.');
    }

    /**
     * Helper: simpan semua dokumen yang diupload,
     * set path file dan flag dok_* = 1 di tabel permohonan_nota_dinas
     */
    protected function handleFileUploads(Request $request, PermohonanNotaDinas $permohonan): void
    {
        $basePath = "permohonan_nota_dinas/{$permohonan->id}";

        $fileFields = [
            'surat_permohonan',
            'profil_perusahaan',
            'ktp_direktur',
            'npwp_perusahaan',
            'akte_pendirian',
            'surat_kesiapan_psu',
            'surat_tidak_sengketa',
            'pkkpr',
            'nib_kbli',
            'peil_banjir',
            'alas_hak',
            'bast_tahap_pengembangan',
            'siteplan_a3',
            'peta_lokasi',
            'site_plan',
            'kontur_tanah',
            'rencana_jalan',
            'rencana_drainase',
            'rencana_rth',
            'rencana_air_bersih',
            'rencana_sanitasi',
            'rencana_fasum_fasos',
        ];

        $updated = false;

        foreach ($fileFields as $field) {
            $uploaded = $request->file($field);

            if ($uploaded) {
                // simpan ke storage/app/public/permohonan_nota_dinas/{id}/{field}/xxx
                $path = $uploaded->store("{$basePath}/{$field}", 'public');

                // simpan path ke kolom varchar
                $permohonan->{$field} = $path;

                // set flag dok_* = 1 (kolom memang sudah ada di DB-mu)
                $permohonan->{'dok_' . $field} = 1;

                $updated = true;
            }
        }

        if ($updated) {
            $permohonan->save();
        }
    }
}