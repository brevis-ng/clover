<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //load helpers folder
        foreach (glob(app_path().'/Helpers/*.php') as $file) {
            require_once($file);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // config(['nutgram.token' => app(GeneralSettings::class)->bot_token]);
    }
}
