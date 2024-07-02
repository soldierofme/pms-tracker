<x-app-layout>
    <div class="container">
        <h2>生理周期設定</h2>
        
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
            <div class="mt-4">
                <h3>予測</h3>
                <p>次回生理予定日: {{ $nextPeriod->format('Y年m月d日') }}</p>
                <p>次回排卵予定日: {{ $ovulation->format('Y年m月d日') }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
