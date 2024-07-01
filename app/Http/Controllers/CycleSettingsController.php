<?php

namespace App\Http\Controllers;

use App\Models\CycleSettings;
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
        $settings = auth()->user()->cycleSettings;
        
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

        return view('cycle-settings.show', compact('settings', 'nextPeriod', 'ovulation', 'periodAlerts', 'ovulationAlerts'));
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

        return redirect()->route('cycle-settings.show')->with('success', '設定が保存されました。');
    }
}