<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator; // --- TAMBAHKAN INI ---

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void // --- TAMBAHKAN PARAMETER INI ---
    {
        // --- TAMBAHKAN BLOK KODE INI ---
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
        // --- AKHIR BLOK KODE INI ---
    }
}