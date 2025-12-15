<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperPerumahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Ambil nama perusahaan terakhir milik developer (dari tabel perumahan)
     */
    private function getLastCompanyName(int $userId): ?string
    {
        return Perumahan::where('user_id', $userId)
            ->whereNotNull('nama_perusahaan')
            ->where('nama_perusahaan', '!=', '')
            ->orderByDesc('created_at')
            ->value('nama_perusahaan');
    }

    /* ==========================
     *  LIST PERUMAHAN SAYA
     * ========================== */
    public function index(Request $request)
    {
        $query = Perumahan::where('user_id', auth()->id());

        // Filter pencarian nama
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('nama', 'like', "%{$q}%");
        }

        // Filter status verifikasi (disetujui / pending / revisi)
        if ($request->filled('status')) {
            if ($request->status === 'revisi') {
                // Revisi = status pending + ada catatan revisi dari dinas
                $query->where('status', 'pending')
                      ->whereNotNull('catatan_revisi');
            } elseif ($request->status === 'pending') {
                // Pending murni = pending + TIDAK ada catatan revisi
                $query->where('status', 'pending')
                      ->whereNull('catatan_revisi');
            } else {
                // disetujui
                $query->where('status', $request->status);
            }
        }

        // Urutkan terbaru
        $projects = $query->orderByDesc('created_at')->get();

        return view('developer.perumahan.index', compact('projects'));
    }

    /* ==========================
     *  FORM TAMBAH
     * ========================== */
    public function create()
    {
        $userId = auth()->id();

        // ✅ ambil nama perusahaan terakhir untuk auto-fill
        $lastCompanyName = $this->getLastCompanyName($userId);

        return view('developer.perumahan.create', compact('lastCompanyName'));
    }

    /* ==========================
     *  SIMPAN PERUMAHAN BARU
     * ========================== */
    public function store(Request $request)
    {
        $userId = auth()->id();

        // VALIDASI
        $data = $request->validate([
            'nama'               => ['required','string','max:150'],
            'lokasi'             => ['required','string','max:255'],
            'lokasi_google_map'  => ['nullable','string','max:2048'],
            'nama_perusahaan'    => ['nullable','string','max:255'],
            'deskripsi'          => ['nullable','string'],
            'telepon'            => ['nullable','string','max:25'],
            'harga'              => ['nullable','numeric','min:0'],
            'tipe'               => ['nullable','string','max:255'],
            'fasilitas'          => ['nullable','string'],

            // status unit (Tersedia / Tidak Tersedia)
            'status_unit'        => ['required', 'in:Tersedia,Tidak Tersedia'],

            // spesifikasi 2 kolom × 3 baris
            'spesifikasi_kiri_1'   => ['nullable','string','max:255'],
            'spesifikasi_kiri_2'   => ['nullable','string','max:255'],
            'spesifikasi_kiri_3'   => ['nullable','string','max:255'],
            'spesifikasi_kanan_1'  => ['nullable','string','max:255'],
            'spesifikasi_kanan_2'  => ['nullable','string','max:255'],
            'spesifikasi_kanan_3'  => ['nullable','string','max:255'],

            // DATA TEKNIS TAMBAHAN
            'tahun_pembangunan' => ['nullable','string','max:50'],
            'luas_tanah'        => ['nullable','numeric','min:0'],
            'luas_bangunan'     => ['nullable','numeric','min:0'],
            'jumlah_unit'       => ['nullable','integer','min:0'],

            // FILE
            'cover'          => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'gallery.*'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'tabel_angsuran' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'denah_rumah'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'dokumen_foto.*' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        // ✅ kalau nama_perusahaan kosong, auto isi dari perumahan terakhir
        if (empty($data['nama_perusahaan'])) {
            $data['nama_perusahaan'] = $this->getLastCompanyName($userId);
        }

        // GABUNG SPESIFIKASI JADI 1 TEXT (dipisah baris baru)
        $spesParts = [
            $data['spesifikasi_kiri_1']  ?? null,
            $data['spesifikasi_kiri_2']  ?? null,
            $data['spesifikasi_kiri_3']  ?? null,
            $data['spesifikasi_kanan_1'] ?? null,
            $data['spesifikasi_kanan_2'] ?? null,
            $data['spesifikasi_kanan_3'] ?? null,
        ];
        $spesifikasiText = implode(PHP_EOL, array_filter($spesParts));

        // BUAT RECORD TANPA FILE DULU
        $perumahan = Perumahan::create([
            'user_id'           => $userId,
            'nama'              => $data['nama'],
            'lokasi'            => $data['lokasi'],
            'lokasi_google_map' => $data['lokasi_google_map'] ?? null,
            'nama_perusahaan'   => $data['nama_perusahaan'] ?? null,
            'nama_developer'    => auth()->user()->name ?? null,
            'deskripsi'         => $data['deskripsi'] ?? null,
            'telepon'           => $data['telepon'] ?? null,
            'harga'             => $data['harga'] !== null ? (int) $data['harga'] : null,
            'tipe'              => $data['tipe'] ?? null,
            'fasilitas'         => $data['fasilitas'] ?? null,
            'spesifikasi'       => $spesifikasiText ?: null,

            // status verifikasi perumahan oleh dinas
            'status'            => 'pending',

            // status unit berdasarkan input
            'status_unit'       => $data['status_unit'] ?? 'Tersedia',

            'tahun_pembangunan' => $data['tahun_pembangunan'] ?? null,
            'luas_tanah'        => $data['luas_tanah'] ?? null,
            'luas_bangunan'     => $data['luas_bangunan'] ?? null,
            'jumlah_unit'       => $data['jumlah_unit'] ?? null,
        ]);

        // SIMPAN FILE
        $basePath      = "perumahan/{$perumahan->id}";
        $coverPath     = null;
        $galleryPaths  = [];
        $docPaths      = [];
        $tabelAngsuran = null;
        $denahPath     = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store("{$basePath}/cover", 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $i => $file) {
                if ($i >= 5) break;
                $galleryPaths[] = $file->store("{$basePath}/gallery", 'public');
            }
        }

        if ($request->hasFile('tabel_angsuran')) {
            $tabelAngsuran = $request->file('tabel_angsuran')
                ->store("{$basePath}/tabel_angsuran", 'public');
        }

        if ($request->hasFile('denah_rumah')) {
            $denahPath = $request->file('denah_rumah')
                ->store("{$basePath}/denah", 'public');
        }

        if ($request->hasFile('dokumen_foto')) {
            foreach ($request->file('dokumen_foto') as $i => $file) {
                if ($i >= 3) break;
                $docPaths[] = $file->store("{$basePath}/dokumen", 'public');
            }
        }

        $perumahan->update([
            'cover'          => $coverPath,
            'gallery'        => $galleryPaths ? json_encode($galleryPaths) : null,
            'tabel_angsuran' => $tabelAngsuran,
            'denah_rumah'    => $denahPath,
            'dokumen_foto'   => $docPaths ? json_encode($docPaths) : null,
        ]);

        return redirect()
            ->route('developer.perumahan.index')
            ->with('status', 'Perumahan dikirim untuk diverifikasi dinas (status: pending).');
    }

    /* ==========================
     *  DETAIL (UNTUK DEVELOPER)
     * ========================== */
    public function show(Perumahan $perumahan)
    {
        abort_unless($perumahan->user_id === auth()->id(), 403);

        return view('developer.perumahan.show', compact('perumahan'));
    }

    /* ==========================
     *  FORM EDIT
     * ========================== */
    public function edit(Perumahan $perumahan)
    {
        abort_unless($perumahan->user_id === auth()->id(), 403);

        return view('developer.perumahan.edit', compact('perumahan'));
    }

    /* ==========================
     *  UPDATE PERUMAHAN
     * ========================== */
    public function update(Request $request, Perumahan $perumahan)
    {
        abort_unless($perumahan->user_id === auth()->id(), 403);

        $userId = auth()->id();

        $data = $request->validate([
            'nama'               => ['required','string','max:150'],
            'lokasi'             => ['required','string','max:255'],
            'lokasi_google_map'  => ['nullable','string','max:2048'],
            'nama_perusahaan'    => ['nullable','string','max:255'],
            'deskripsi'          => ['nullable','string'],
            'telepon'            => ['nullable','string','max:25'],
            'harga'              => ['nullable','numeric','min:0'],
            'tipe'               => ['nullable','string','max:255'],
            'fasilitas'          => ['nullable','string'],

            'status_unit'        => ['nullable', 'in:Tersedia,Tidak Tersedia'],

            'spesifikasi_kiri_1'   => ['nullable','string','max:255'],
            'spesifikasi_kiri_2'   => ['nullable','string','max:255'],
            'spesifikasi_kiri_3'   => ['nullable','string','max:255'],
            'spesifikasi_kanan_1'  => ['nullable','string','max:255'],
            'spesifikasi_kanan_2'  => ['nullable','string','max:255'],
            'spesifikasi_kanan_3'  => ['nullable','string','max:255'],

            'tahun_pembangunan' => ['nullable','string','max:50'],
            'luas_tanah'        => ['nullable','numeric','min:0'],
            'luas_bangunan'     => ['nullable','numeric','min:0'],
            'jumlah_unit'       => ['nullable','integer','min:0'],

            'cover'          => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'gallery.*'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'tabel_angsuran' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'denah_rumah'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'dokumen_foto.*' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        // ✅ optional: kalau nama_perusahaan kosong, fallback dari terakhir
        if (empty($data['nama_perusahaan'])) {
            $data['nama_perusahaan'] = $this->getLastCompanyName($userId) ?? $perumahan->nama_perusahaan;
        }

        $spesParts = [
            $data['spesifikasi_kiri_1']  ?? null,
            $data['spesifikasi_kiri_2']  ?? null,
            $data['spesifikasi_kiri_3']  ?? null,
            $data['spesifikasi_kanan_1'] ?? null,
            $data['spesifikasi_kanan_2'] ?? null,
            $data['spesifikasi_kanan_3'] ?? null,
        ];
        $spesifikasiText = implode(PHP_EOL, array_filter($spesParts));

        $updateData = [
            'nama'              => $data['nama'],
            'lokasi'            => $data['lokasi'],
            'lokasi_google_map' => $data['lokasi_google_map'] ?? null,
            'nama_perusahaan'   => $data['nama_perusahaan'] ?? null,
            'deskripsi'         => $data['deskripsi'] ?? null,
            'telepon'           => $data['telepon'] ?? null,
            'harga'             => $data['harga'] !== null ? (int) $data['harga'] : null,
            'tipe'              => $data['tipe'] ?? null,
            'fasilitas'         => $data['fasilitas'] ?? null,
            'spesifikasi'       => $spesifikasiText ?: null,
            'tahun_pembangunan' => $data['tahun_pembangunan'] ?? null,
            'luas_tanah'        => $data['luas_tanah'] ?? null,
            'luas_bangunan'     => $data['luas_bangunan'] ?? null,
            'jumlah_unit'       => $data['jumlah_unit'] ?? null,

            'status_unit'       => $data['status_unit'] ?? $perumahan->status_unit,

            // edit => pending ulang
            'status'      => 'pending',
            'approved_at' => null,
            'approved_by' => null,
        ];

        if ($perumahan->isFillable('catatan_revisi')) {
            $updateData['catatan_revisi'] = null;
        }

        $perumahan->update($updateData);

        $basePath = "perumahan/{$perumahan->id}";

        if ($request->hasFile('cover')) {
            if ($perumahan->cover) Storage::disk('public')->delete($perumahan->cover);
            $coverPath = $request->file('cover')->store("{$basePath}/cover", 'public');
            $perumahan->update(['cover' => $coverPath]);
        }

        if ($request->hasFile('gallery')) {
            if ($perumahan->gallery) {
                $oldGallery = is_array($perumahan->gallery)
                    ? $perumahan->gallery
                    : (json_decode($perumahan->gallery, true) ?: []);
                foreach ($oldGallery as $old) Storage::disk('public')->delete($old);
            }

            $galleryPaths = [];
            foreach ($request->file('gallery') as $i => $file) {
                if ($i >= 5) break;
                $galleryPaths[] = $file->store("{$basePath}/gallery", 'public');
            }
            $perumahan->update(['gallery' => $galleryPaths ? json_encode($galleryPaths) : null]);
        }

        if ($request->hasFile('tabel_angsuran')) {
            if ($perumahan->tabel_angsuran) Storage::disk('public')->delete($perumahan->tabel_angsuran);
            $path = $request->file('tabel_angsuran')->store("{$basePath}/tabel_angsuran", 'public');
            $perumahan->update(['tabel_angsuran' => $path]);
        }

        if ($request->hasFile('denah_rumah')) {
            if ($perumahan->denah_rumah) Storage::disk('public')->delete($perumahan->denah_rumah);
            $path = $request->file('denah_rumah')->store("{$basePath}/denah", 'public');
            $perumahan->update(['denah_rumah' => $path]);
        }

        if ($request->hasFile('dokumen_foto')) {
            if ($perumahan->dokumen_foto) {
                $oldDocs = is_array($perumahan->dokumen_foto)
                    ? $perumahan->dokumen_foto
                    : (json_decode($perumahan->dokumen_foto, true) ?: []);
                foreach ($oldDocs as $old) Storage::disk('public')->delete($old);
            }

            $docPaths = [];
            foreach ($request->file('dokumen_foto') as $i => $file) {
                if ($i >= 3) break;
                $docPaths[] = $file->store("{$basePath}/dokumen", 'public');
            }
            $perumahan->update(['dokumen_foto' => $docPaths ? json_encode($docPaths) : null]);
        }

        return redirect()
            ->route('developer.perumahan.show', $perumahan->id)
            ->with('status', 'Data perumahan berhasil diperbarui. Menunggu verifikasi ulang dari dinas.');
    }
}