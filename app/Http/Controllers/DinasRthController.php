<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseRtdbService;
use Illuminate\Http\Request;

class DinasRthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // opsional kalau kamu punya middleware role:
        // $this->middleware('role:dinas');
    }

    /**
     * INDEX DINAS:
     * list perumahan + developer yang sudah punya alat RTH (device_id + perumahan terpilih)
     */
    public function index(Request $request)
    {
        $onlyApproved = $request->boolean('approved', false); // /dinas/rth?approved=1

        $q = User::query()
            ->where('role', 'developer')
            ->whereNotNull('rth_device_id')
            ->whereNotNull('rth_perumahan_id')
            ->with(['perumahanRth']) // relasi di User model
            ->orderByDesc('id');

        // OPTIONAL: hanya yang perumahannya sudah diverifikasi
        if ($onlyApproved) {
            $q->whereHas('perumahanRth', function ($qq) {
                $qq->where('status_verifikasi', 'approved');
            });
        }

        $developers = $q->get();

        return view('dinas.rth.index', [
            'developers' => $developers,
            'onlyApproved' => $onlyApproved,
        ]);
    }

    /**
     * AJAX: ambil status singkat dari Firebase untuk daftar device id.
     * Return: { "dev_001": { ok:true, active:true, updatedAtMs:..., pump:... }, ... }
     */
    public function status(Request $request, FirebaseRtdbService $fb)
    {
        // dukung dua format:
        // 1) /dinas/rth/status?ids=dev_001,dev_002
        // 2) /dinas/rth/status?ids[]=dev_001&ids[]=dev_002
        $ids = $request->query('ids', []);

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids)));
        } elseif (is_array($ids)) {
            $ids = array_filter(array_map('trim', $ids));
        } else {
            $ids = [];
        }

        // batasi biar tidak berat
        $ids = array_values(array_unique($ids));
        $ids = array_slice($ids, 0, 50);

        if (empty($ids)) {
            return response()->json(['ok' => true, 'data' => (object)[]]);
        }

        $nowMs = (int) round(microtime(true) * 1000);
        $activeWindowMs = 2 * 60 * 1000; // 2 menit dianggap "aktif"

        $out = [];

        foreach ($ids as $deviceId) {
            // basic sanitize: hanya huruf/angka/_/-
            if (!preg_match('/^[A-Za-z0-9_\-]{3,40}$/', $deviceId)) {
                $out[$deviceId] = [
                    'ok' => false,
                    'active' => false,
                    'message' => 'Invalid device id format',
                    'updatedAtMs' => null,
                    'pump' => null,
                ];
                continue;
            }

            $data = $fb->getDevice($deviceId);

            if (isset($data['_error'])) {
                $out[$deviceId] = [
                    'ok' => false,
                    'active' => false,
                    'message' => "Firebase error ({$data['_status']})",
                    'updatedAtMs' => null,
                    'pump' => null,
                ];
                continue;
            }

            $updated = isset($data['updatedAtMs']) ? (int) $data['updatedAtMs'] : null;
            $active = $updated ? (($nowMs - $updated) <= $activeWindowMs) : false;

            $pumpOn = (string)($data['pump'] ?? '0') === '1' || ($data['pump'] ?? false) === true;

            $out[$deviceId] = [
                'ok' => true,
                'active' => $active,
                'updatedAtMs' => $updated,
                'pump' => $pumpOn ? 1 : 0,
            ];
        }

        return response()->json(['ok' => true, 'data' => $out]);
    }
}