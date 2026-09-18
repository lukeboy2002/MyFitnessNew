<x-card.default variant="outline"
                class="w-full">

    {{-- Header --}}
    <div class="flex items-center gap-2 mb-5">
        <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
            <x-lucide-calendar-days class="w-5 h-5"/>
        </div>
        <div>
            <h2 class="font-semibold text-primary">
                {{ __('This week') }}
            </h2>
            <p class="text-xs text-muted">
                {{ now()->startOfWeek()->format('d M') }}
                -
                {{ now()->endOfWeek()->format('d M') }}
            </p>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-3">
        {{-- Workouts --}}
        <x-card.small variant="outline"
                      icon="dumbbell"
                      title="{{ __('Workouts') }}">
            <x-slot:description>
                {{ $workoutsThisWeek }}
            </x-slot:description>
        </x-card.small>
        {{-- Sets --}}
        <x-card.small variant="outline"
                      icon="check"
                      title="{{ __('Sets') }}">
            <x-slot:description>
                {{ $totalSetsThisWeek }}
            </x-slot:description>
        </x-card.small>
        {{-- Training Time --}}
        <x-card.small variant="outline"
                      icon="clock"
                      title="{{ __('Time') }}">
            <x-slot:description>
                {{ $totalTrainingTimeThisWeek }}
            </x-slot:description>
        </x-card.small>
    </div>
</x-card.default>
