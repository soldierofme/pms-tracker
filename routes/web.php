<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CycleSettingsController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\CalendarSettingController;
use App\Http\Controllers\HealthChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ... (既存のコードはそのまま)

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

    // 健康状態質問チャットのルート
    Route::get('/health-chat', [HealthChatController::class, 'index'])->name('health-chat.index');
});

require __DIR__.'/auth.php';

// データベース接続テスト用ルート
Route::get('/test-db-connection', function () {
    try {
        \DB::connection()->getPdo();
        return 'Database connection is successful.';
    } catch (\Exception $e) {
        return 'Failed to connect to the database: ' . $e->getMessage();
    }
});

Route::get('auth/google', [GoogleCalendarController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
