<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarSetting;

class CalendarSettingController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        $calendarSetting = $user->calendarSetting ?? new CalendarSetting();

        $calendarSetting->user_id = $user->id;
        $calendarSetting->record_menstrual_dates = $request->has('record_menstrual_dates');
        $calendarSetting->record_ovulation_dates = $request->has('record_ovulation_dates');
        $calendarSetting->days_before = $request->days_before;

        $calendarSetting->save();

        return redirect()->back()->with('success', 'カレンダー設定が更新されました。');
    }
}