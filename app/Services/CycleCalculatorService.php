<?php

namespace App\Services;

use Carbon\Carbon;

class CycleCalculatorService
{
    /**
     * 次の生理予定日を計算します
     *
     * @param string $lastPeriodStart 最後の生理開始日 (Y-m-d形式)
     * @param int $cycleLength 生理周期の長さ（日数）
     * @return Carbon
     */
    public function calculateNextPeriod(string $lastPeriodStart, int $cycleLength): Carbon
    {
        return Carbon::parse($lastPeriodStart)->addDays($cycleLength);
    }

    /**
     * 排卵予定日を計算します
     *
     * @param string $lastPeriodStart 最後の生理開始日 (Y-m-d形式)
     * @return Carbon
     */
    public function calculateOvulation(string $lastPeriodStart): Carbon
    {
        return Carbon::parse($lastPeriodStart)->addDays(14);
    }

    /**
     * 生理予定日と排卵予定日のアラート日を計算します
     *
     * @param Carbon $targetDate 対象日（生理予定日または排卵予定日）
     * @return array アラート日の配列
     */
    public function calculateAlertDays(Carbon $targetDate): array
    {
        return [
            $targetDate->copy()->subDays(3),
            $targetDate->copy()->subDays(2),
            $targetDate->copy()->subDays(1),
            $targetDate->copy(),
        ];
    }
}