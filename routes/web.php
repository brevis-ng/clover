<?php

use App\Http\Livewire\Index;
use App\Http\Livewire\OrderSubmit;
use App\Http\Livewire\ShowCarts;
use App\Http\Middleware\SetLanguage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::view("/", "welcome");

Route::prefix("/webapp")
    ->middleware(SetLanguage::class)
    ->group(function () {
        Route::get("/", Index::class)->name("frontend.index");
        Route::get("/carts", ShowCarts::class)->name("frontend.carts");
        Route::get("/order-placed", OrderSubmit::class)->name("frontend.orderplaced");
    });

Route::get("admin/clear-cache", function () {
    Artisan::call("cache:clear");
    \Filament\Notifications\Notification::make()
        ->title("Cache was cleared successfully")
        ->success()
        ->send();
    return redirect()->back();
})->name("backend.clear");
