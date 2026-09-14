<div class="flex flex-col gap-5">

    {{-- Latest --}}
    @if ($progressData->isNotEmpty())
        @php
            $latest = $progressData->last();
        @endphp

        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-muted">{{ __('Latest') }}</p>
                <p class="text-lg font-semibold text-primary">
                    {{ $formatter->formatMetric(
                        $metric,
                        $latest['value']
                    ) }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-muted">{{ $latest['date'] }}</p>
            </div>
        </div>
    @endif


    {{-- Metric buttons --}}
    <div class="flex gap-2 overflow-x-auto">
        @foreach ($metrics as $key => $label)
            <button type="button"
                    wire:click="setMetric('{{ $key }}')"
                    class="shrink-0 rounded-lg px-4 py-2 text-sm font-medium transition
                    {{ $metric === $key
                        ? 'bg-secondary text-primary'
                        : 'bg-surface-hover text-secondary hover:text-primary'
                    }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="rounded-xl border border-border bg-surface p-5">
        @if ($progressData->isEmpty())
            <div class="flex min-h-64 flex-col items-center justify-center text-center">
                <x-lucide-chart-no-axes-combined class="size-8 text-muted"/>
                <p class="mt-3 text-sm text-muted">
                    {{ __('Not enough data to show progress yet.') }}
                </p>
            </div>
        @else
            <div class="h-72"
                 x-data
                 x-init="
                    setTimeout(() => {
                        window.createExerciseChart(
                            $refs.chart,
                            @js($chartData['labels']),
                            @js($chartData['values'])
                        );
                    }, 50)
                "
            >
                <canvas wire:ignore
                        x-ref="chart"
                        data-chart-id="exercise-progress-{{ $exercise->id }}"
                >
                </canvas>
            </div>
        @endif
    </div>


    <script>
        Livewire.on(
            'progress-chart-updated',

            (event) => {
                const payload =
                    event[0] ?? event;
                const data =
                    payload.chartData ??
                    payload;
                if (!data) {
                    return;
                }

                const canvas =
                    document.querySelector(
                        '[data-chart-id="exercise-progress-{{ $exercise->id }}"]'
                    );

                if (!canvas) {
                    return;
                }

                window.createExerciseChart(
                    canvas,
                    data.labels,
                    data.values
                );
            }
        );
    </script>
</div>
