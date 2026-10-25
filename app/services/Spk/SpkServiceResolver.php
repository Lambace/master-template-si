<?php

namespace App\Services\Spk;

use App\Models\Setting;
use App\Services\PerhitunganSAW;

class SpkServiceResolver
{
    /**
     * Pilih service berdasarkan pengaturan 'spk_method'.
     * Saat ini hanya SAW yang aktif; metode lain akan ditambahkan di Fase 2+.
     */
    public static function resolve()
    {
        $metode = Setting::get('spk_method', 'saw');

        return match ($metode) {
            'wp'     => app(WpService::class),
            // 'topsis' => app(TopsisService::class),
            // 'moora'  => app(MooraService::class),
            default  => app(PerhitunganSAW::class),
        };
    }
}