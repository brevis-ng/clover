<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //load helpers folder
        foreach (glob(app_path() . "/Helpers/*.php") as $file) {
            require_once $file;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // config(['nutgram.token' => app(GeneralSettings::class)->bot_token]);
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label("Clear cache")
                    ->url(route("backend.clear"))
                    ->icon("heroicon-o-trash"),
            ]);
        });
    }
}
