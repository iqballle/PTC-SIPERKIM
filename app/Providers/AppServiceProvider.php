<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Perumahan;
use App\Models\PermohonanNotaDinas;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /**
         * ==========================
         * BADGE / NOTIF: DEVELOPER
         * ==========================
         * Yang dihitung: item yang PERLU REVISI
         */
        View::composer('developer.*', function ($view) {
            $count = 0;

            if (auth()->check() && auth()->user()->role === 'developer') {
                $userId = auth()->id();

                // Perumahan perlu revisi = pending + ada catatan_revisi
                $countPerumahan = Perumahan::query()
                    ->where('user_id', $userId)
                    ->where('status', 'pending')
                    ->whereNotNull('catatan_revisi')
                    ->count();

                // Nota dinas perlu revisi = status revisi
                $countNota = PermohonanNotaDinas::query()
                    ->where('user_id', $userId)
                    ->where('status', 'revisi')
                    ->count();

                $count = $countPerumahan + $countNota;
            }

            // variabel untuk sidebar developer
            $view->with('devRevisiCount', $count);
        });

        /**
         * ==========================
         * BADGE / NOTIF: DINAS
         * ==========================
         * Yang dihitung: PERMINTAAN MASUK untuk diverifikasi (pending)
         */
        View::composer('dinas.*', function ($view) {

            $perumahanMasuk = 0;
            $notaMasuk = 0;

            if (auth()->check() && auth()->user()->role === 'dinas') {

                // Perumahan masuk (pending murni = belum ada catatan revisi)
                $perumahanMasuk = Perumahan::query()
                    ->where('status', 'pending')
                    ->whereNull('catatan_revisi')
                    ->count();

                // Nota dinas masuk (pending)
                $notaMasuk = PermohonanNotaDinas::query()
                    ->where('status', 'pending')
                    ->count();
            }

            // variabel untuk sidebar dinas
            $view->with('dinasPerumahanMasukCount', $perumahanMasuk);
            $view->with('dinasNotaMasukCount', $notaMasuk);
        });
    }
}