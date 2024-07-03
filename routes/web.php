<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CycleSettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\CalendarSettingController;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// Googleカレンダーの認証ルート
Route::get('auth/google', [GoogleCalendarController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CycleSettingsController::class, 'show'])->name('dashboard');
    Route::post('/cycle-settings', [CycleSettingsController::class, 'store'])->name('cycle-settings.store');
    Route::get('/cycle-settings', [CycleSettingsController::class, 'show'])->name('cycle-settings.show');

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 新しいカレンダー同期ルートをここに追加
    Route::post('/calendar/sync', [GoogleCalendarController::class, 'sync'])->name('calendar.sync');

    // カレンダー設定更新ルート
    Route::put('/calendar-settings', [CalendarSettingController::class, 'update'])->name('calendar.settings.update');
});

// データベース接続テスト用ルート
Route::get('/test-db-connection', function () {
    try {
        \DB::connection()->getPdo();
        return 'Database connection is successful.';
    } catch (\Exception $e) {
        return 'Failed to connect to the database: ' . $e->getMessage();
    }
});
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

require __DIR__.'/auth.php';
