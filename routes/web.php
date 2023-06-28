<?php

use App\Http\Livewire\Index;
use App\Http\Livewire\ShowCarts;
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

Route::get('/', Index::class)->name('frontend.index');
Route::get('/carts', ShowCarts::class)->name('frontend.carts');
