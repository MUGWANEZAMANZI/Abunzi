<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Vonage\Client;
use GuzzleHttp\Client as GuzzleClient;
use Vonage\Client\Credentials\Basic;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
        $guzzle = new GuzzleClient([
            'verify' => config('services.vonage_ssl_cert'),
        ]);

        $credentials = new Basic(
            env('VONAGE_KEY'),
            env('VONAGE_SECRET')
        );

        return new Client($credentials, ['client' => $guzzle]);
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
