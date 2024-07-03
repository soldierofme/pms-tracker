<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CalendarSetting;

class GoogleCalendarController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes(config('services.google.scopes'))->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        $googleUser = auth()->user();
        $googleUser->google_token = $user->token;
        $googleUser->save();

        return redirect()->route('dashboard');
    }

    public function addMenstrualCycleEvents(Request $request)
    {
        $user = auth()->user();
        // ユーザーのカレンダー設定を取得
        $settings = $user->calendarSetting ?? new CalendarSetting();

        if (!$user->google_token) {
            return redirect()->route('auth.google');
        }

        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessToken($user->google_token);

        if ($client->isAccessTokenExpired()) {
            return redirect()->route('auth.google');
        }

        $service = new GoogleCalendar($client);

        $cycles = $this->calculateMenstrualCycles($user, $settings->days_before ?? 7);

        foreach ($cycles as $cycle) {
            if ($settings->record_menstrual_dates ?? true) {
                $this->addEventToCalendar($service, '生理予定日', $cycle['menstrual_start'], $cycle['menstrual_end']);
            }
            if ($settings->record_ovulation_dates ?? false) {
                $this->addEventToCalendar($service, '排卵予定日', $cycle['ovulation_date'], $cycle['ovulation_date']->copy()->addDay());
            }
        }

        return redirect()->route('dashboard')->with('success', '予定がGoogleカレンダーに追加されました。');
    }

    private function calculateMenstrualCycles($user, $daysBefore)
    {
        $cycleLength = $user->cycle_length ?? 28;
        $periodLength = $user->period_length ?? 5;
        $ovulationDay = $cycleLength - 14; // 排卵日は通常、次の生理開始の14日前

        $cycles = [];
        $startDate = $user->last_period_date ?? Carbon::now()->subDays($daysBefore);

        for ($i = 0; $i < 3; $i++) {
            $cycleStartDate = $startDate->copy()->addDays($cycleLength * $i);
            $cycles[] = [
                'menstrual_start' => $cycleStartDate,
                'menstrual_end' => $cycleStartDate->copy()->addDays($periodLength),
                'ovulation_date' => $cycleStartDate->copy()->addDays($ovulationDay),
            ];
        }

        return $cycles;
    }

    private function addEventToCalendar($service, $summary, $start, $end)
    {
        $event = new \Google_Service_Calendar_Event([
            'summary' => $summary,
            'start' => ['date' => $start->toDateString()],
            'end' => ['date' => $end->toDateString()],
        ]);

        $service->events->insert('primary', $event);
    }

    public function sync(Request $request)
    {
        $this->addMenstrualCycleEvents($request);

        return redirect()->back()->with('success', 'カレンダーが正常に同期されました');
    }
}
