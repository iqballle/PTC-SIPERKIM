<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseRtdbService
{
    private string $dbUrl;
    private string $secret;
    private string $base;

    public function __construct()
    {
        $this->dbUrl  = rtrim(config('services.firebase.db_url'), '/');
        $this->secret = (string) config('services.firebase.db_secret');
        $this->base   = trim((string) config('services.firebase.rth_base', 'realtime/devices'), '/');
    }

    private function url(string $path): string
    {
        $path = ltrim($path, '/');

        return "{$this->dbUrl}/{$path}.json?auth={$this->secret}";
    }

    /** Ambil 1 device data (return array) */
    public function getDevice(string $deviceId): array
    {
        $path = "{$this->base}/{$deviceId}";
        $res = Http::timeout(8)->get($this->url($path));

        if (!$res->successful()) {
            return [
                '_error' => true,
                '_status' => $res->status(),
                '_body' => $res->body(),
            ];
        }

        return $res->json() ?? [];
    }

    /** Set pump langsung (0/1) */
    public function setPump(string $deviceId, int $value): bool
    {
        $value = $value ? 1 : 0;
        $path = "{$this->base}/{$deviceId}/pump";

        $res = Http::timeout(8)->put($this->url($path), $value);

        return $res->successful();
    }

    /**
     * Mode lebih aman: kirim command.
     * Nanti ESP32 bisa baca /cmd/pump dan eksekusi.
     */
    public function sendCommand(string $deviceId, array $payload): bool
    {
        $path = "{$this->base}/{$deviceId}/cmd";

        $res = Http::timeout(8)->patch($this->url($path), $payload);

        return $res->successful();
    }
}