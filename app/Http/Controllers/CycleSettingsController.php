<?php

namespace App\Http\Controllers;

use App\Models\CycleSettings;
use App\Models\CalendarSetting;
use App\Services\CycleCalculatorService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CycleSettingsController extends Controller
{
    private $calculator;

    public function __construct(CycleCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function show()
    {
        $user = auth()->user();
        $settings = $user->cycleSettings;
        $calendarSettings = $user->calendarSetting ?? new CalendarSetting();
        
        if ($settings) {
            $nextPeriod = $this->calculator->calculateNextPeriod($settings->last_period_start_date, $settings->cycle_length);
            $ovulation = $this->calculator->calculateOvulation($settings->last_period_start_date);
            $periodAlerts = $this->calculator->calculateAlertDays($nextPeriod);
            $ovulationAlerts = $this->calculator->calculateAlertDays($ovulation);
        } else {
            $nextPeriod = null;
            $ovulation = null;
            $periodAlerts = [];
            $ovulationAlerts = [];
        }

        return view('dashboard', compact('settings', 'calendarSettings', 'nextPeriod', 'ovulation', 'periodAlerts', 'ovulationAlerts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cycle_length' => 'required|integer|min:20|max:40',
            'last_period_start_date' => 'required|date',
        ]);

        CycleSettings::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only(['cycle_length', 'last_period_start_date'])
        );

        return redirect()->route('dashboard')->with('success', '設定が保存されました。');
    }
}