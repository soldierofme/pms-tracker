<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class SendCycleAlerts extends Command
{
    protected $signature = 'cycle:send-alerts';
    protected $description = 'Send alerts for upcoming period and ovulation';

    public function handle()
    {
        $users = User::has('cycleSettings')->get();

        foreach ($users as $user) {
            $settings = $user->cycleSettings;
            $nextPeriod = Carbon::parse($settings->last_period_start_date)->addDays($settings->cycle_length);
            $ovulation = Carbon::parse($settings->last_period_start_date)->addDays(14);

            if ($nextPeriod->isToday() || $nextPeriod->isTomorrow()) {
                // 生理予定日のアラート送信ロジック
                $this->info("生理予定日アラートを {$user->email} に送信しました。");
            }

            if ($ovulation->isToday() || $ovulation->isTomorrow()) {
                // 排卵日のアラート送信ロジック
                $this->info("排卵日アラートを {$user->email} に送信しました。");
            }
        }
    }
}