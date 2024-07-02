<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CycleSettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CycleSettingsController::class, 'show'])->name('dashboard');
    Route::post('/cycle-settings', [CycleSettingsController::class, 'store'])->name('cycle-settings.store');
    Route::get('/cycle-settings', [CycleSettingsController::class, 'show'])->name('cycle-settings.show');  // GETリクエスト用ルート

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
