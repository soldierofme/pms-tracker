<x-app-layout>
    <div class="container">
        <h2>生理の周期入力</h2>
        
        <form method="POST" action="{{ route('cycle-settings.store') }}">
            @csrf
            <div class="form-group">
                <label for="cycle_length">生理周期の長さ（日数）:</label>
                <input type="number" class="form-control" id="cycle_length" name="cycle_length" value="{{ $settings->cycle_length ?? 28 }}" required>
            </div>
            <div class="form-group">
                <label for="last_period_start_date">最後の生理開始日:</label>
                <input type="date" class="form-control" id="last_period_start_date" name="last_period_start_date" value="{{ $settings->last_period_start_date ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>

        @if($nextPeriod && $ovulation)
            <div class="mt-8">
                <h3 class="text-xl font-bold mb-2">予測</h3>
                <p>次回生理予定日: {{ $nextPeriod->format('Y年m月d日') }}</p>
                <p>次回排卵予定日: {{ $ovulation->format('Y年m月d日') }}</p>
            </div>
        @endif
    </div>

    <!-- 新しく追加する部分 -->
    <div class="container mt-8">
        <h2 class="text-2xl font-bold mb-4">Google カレンダー設定</h2>
        
        <form action="{{ route('calendar.settings.update') }}" method="POST" class="mb-6">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="record_menstrual_dates" value="1" {{ $calendarSettings->record_menstrual_dates ? 'checked' : '' }} class="form-checkbox">
                    <span class="ml-2">生理予定日を記録する</span>
                </label>
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="record_ovulation_dates" value="1" {{ $calendarSettings->record_ovulation_dates ? 'checked' : '' }} class="form-checkbox">
                    <span class="ml-2">排卵予定日を記録する</span>
                </label>
            </div>
            <div class="mb-4">
                <label class="block">
                    <span class="text-gray-700">何日前から記録するか</span>
                    <input type="number" name="days_before" value="{{ $calendarSettings->days_before }}" min="0" max="30" class="form-input mt-1 block w-full">
                </label>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    設定を保存
                </button>
            </div>
        </form>

        <form action="{{ route('calendar.sync') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Google カレンダーに転記する
            </button>
        </form>
    </div>
</x-app-layout>
