<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">生理周期設定</h2>
                    
                    <form method="POST" action="{{ route('cycle-settings.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="cycle_length" class="block text-sm font-medium text-gray-700">生理周期の長さ（日数）:</label>
                            <input type="number" class="mt-1 block w-full" id="cycle_length" name="cycle_length" value="{{ $settings->cycle_length ?? 28 }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="last_period_start_date" class="block text-sm font-medium text-gray-700">最後の生理開始日:</label>
                            <input type="date" class="mt-1 block w-full" id="last_period_start_date" name="last_period_start_date" value="{{ $settings->last_period_start_date ?? '' }}" required>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            保存
                        </button>
                    </form>

                    @if($nextPeriod && $ovulation)
                        <div class="mt-8">
                            <h3 class="text-xl font-bold mb-2">予測</h3>
                            <p>次回生理予定日: {{ $nextPeriod->format('Y年m月d日') }}</p>
                            <p>排卵予定日: {{ $ovulation->format('Y年m月d日') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>