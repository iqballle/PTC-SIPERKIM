<?php

namespace App\Http\Controllers;

use App\Models\Perumahan;
use App\Models\User;
use App\Services\FirebaseRtdbService;
use Illuminate\Http\Request;

class DeveloperRthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * HALAMAN SETUP (PILIH PERUMAHAN + DEVICE ID)
     * Sidebar RTH masuk sini dulu.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // ✅ daftar perumahan milik developer ini
        // Jika FK bukan user_id, ganti where('developer_id', $user->id)
        $perumahans = Perumahan::query()
            ->where('user_id', $user->id)
            // ✅ kalau kamu mau hanya yang terverifikasi dinas, buka ini:
            // ->where('status_verifikasi', 'verified')
            ->orderByDesc('created_at')
            ->get();

        $selectedPerumahan = null;
        if (!empty($user->rth_perumahan_id)) {
            $selectedPerumahan = $perumahans->firstWhere('id', (int) $user->rth_perumahan_id);
        }

        return view('developer.rth.index', [
            'deviceId' => $user->rth_device_id,
            'perumahans' => $perumahans,
            'selectedPerumahan' => $selectedPerumahan,
        ]);
    }

    /**
     * SIMPAN PILIHAN PERUMAHAN + SIMPAN/GANTI DEVICE ID
     * Setelah simpan => jika device id ada -> redirect ke monitoring.
     */
    public function bind(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'perumahan_id'  => ['required', 'integer'],
            'rth_device_id' => ['nullable', 'string', 'min:3', 'max:40'],
        ]);

        // ✅ pastikan perumahan milik developer ini
        $perumahanOk = Perumahan::query()
            ->where('id', (int) $data['perumahan_id'])
            ->where('user_id', $user->id)
            // ✅ jika hanya perumahan terverifikasi yang boleh dipilih, aktifkan:
            // ->where('status_verifikasi', 'verified')
            ->exists();

        if (!$perumahanOk) {
            return back()
                ->withErrors(['perumahan_id' => 'Perumahan tidak valid / bukan milik akun ini / belum terverifikasi.'])
                ->withInput();
        }

        $update = [
            'rth_perumahan_id' => (int) $data['perumahan_id'],
        ];

        // device id boleh diisi sekarang, atau nanti
        if (!empty($data['rth_device_id'])) {
            $id = trim($data['rth_device_id']);
            $id = preg_replace('/\s+/', '_', $id);
            $update['rth_device_id'] = $id;
        }

        // ✅ update user (tanpa save)
        User::where('id', $user->id)->update($update);

        // ✅ ambil data terbaru
        $fresh = User::find($user->id);

        if (empty($fresh->rth_device_id)) {
            return redirect()
                ->route('developer.rth.index')
                ->with('status', 'Perumahan berhasil dipilih. Silakan isi Device ID untuk mulai monitoring.');
        }

        return redirect()
            ->route('developer.rth.monitor')
            ->with('status', 'Perumahan & Device ID tersimpan. Monitoring siap digunakan.');
    }

    /**
     * HALAMAN MONITORING
     * Menampilkan nama perumahan yang dipilih.
     */
    public function monitor(Request $request, FirebaseRtdbService $fb)
    {
        $user = $request->user()->fresh();

        if (empty($user->rth_perumahan_id)) {
            return redirect()->route('developer.rth.index')
                ->with('status', 'Silakan pilih perumahan dulu.');
        }

        if (empty($user->rth_device_id)) {
            return redirect()->route('developer.rth.index')
                ->with('status', 'Silakan isi Device ID dulu untuk mulai monitoring.');
        }

        // ✅ ambil perumahan milik developer ini
        $perumahan = Perumahan::query()
            ->where('id', (int) $user->rth_perumahan_id)
            ->where('user_id', $user->id)
            // ✅ jika hanya verified, aktifkan:
            // ->where('status_verifikasi', 'verified')
            ->first();

        if (!$perumahan) {
            return redirect()->route('developer.rth.index')
                ->with('status', 'Perumahan tidak ditemukan / bukan milik akun ini.');
        }

        $deviceId = $user->rth_device_id;

        $data = $fb->getDevice($deviceId);
        $error = isset($data['_error'])
            ? "Firebase error ({$data['_status']}): {$data['_body']}"
            : null;

        return view('developer.rth.monitor', [
            'deviceId'  => $deviceId,
            'perumahan' => $perumahan,
            'data'      => $error ? null : $data,
            'error'     => $error,
        ]);
    }

    /**
     * AJAX polling (dipakai di monitoring.blade.php)
     */
    public function fetch(Request $request, FirebaseRtdbService $fb)
    {
        $user = $request->user()->fresh();

        if (empty($user->rth_perumahan_id)) {
            return response()->json([
                'ok' => false,
                'message' => 'Perumahan belum dipilih',
            ], 422);
        }

        if (empty($user->rth_device_id)) {
            return response()->json([
                'ok' => false,
                'message' => 'rth_device_id belum ada',
            ], 422);
        }

        $data = $fb->getDevice($user->rth_device_id);

        if (isset($data['_error'])) {
            return response()->json([
                'ok' => false,
                'message' => "Firebase error ({$data['_status']})",
                'detail' => $data['_body'] ?? null,
            ], 502);
        }

        return response()->json([
            'ok' => true,
            'data' => $data,
        ]);
    }

    /**
     * OPSIONAL (monitoring-only? boleh hapus ini + route-nya)
     */
    public function setPump(Request $request, FirebaseRtdbService $fb)
    {
        $request->validate([
            'pump' => ['required', 'in:0,1'],
        ]);

        $user = $request->user()->fresh();

        if (empty($user->rth_device_id)) {
            return response()->json([
                'ok' => false,
                'message' => 'rth_device_id belum diisi.',
            ], 422);
        }

        $ok = $fb->setPump($user->rth_device_id, (int) $request->pump);

        return response()->json([
            'ok' => $ok,
            'message' => $ok ? 'Perintah pompa dikirim.' : 'Gagal kirim perintah ke Firebase.',
        ], $ok ? 200 : 502);
    }
}