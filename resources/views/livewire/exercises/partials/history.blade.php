<div class="flex flex-col gap-4">

    @forelse ($history as $item)

        @php
            $statistics = $item['statistics'];
        @endphp

        <div
            class="overflow-hidden rounded-xl border border-border bg-surface"
        >

            {{-- Header --}}
            <div
                class="flex items-start justify-between gap-4 border-b border-border p-4"
            >

                <div>

                    <div class="font-semibold text-primary">

                        {{ $item['date']?->format('d M Y') }}

                    </div>


                    @if ($item['workout'])

                        <div class="mt-1 text-sm text-muted">

                            {{ $item['workout'] }}

                        </div>

                    @endif

                </div>


                {{-- Number of sets --}}
                <div
                    class="rounded-lg bg-surface-hover px-3 py-1 text-xs text-muted"
                >

                    {{ $statistics['sets'] }}

                    {{ __('sets') }}

                </div>

            </div>


            {{-- Strength --}}
            @if ($exercise->type->value === 'strength')

                <div
                    class="grid grid-cols-2 gap-px bg-border"
                >

                    {{-- Volume --}}
                    <div
                        class="bg-surface p-4"
                    >

                        <div class="text-xs text-muted">

                            {{ __('Volume') }}

                        </div>


                        <div class="mt-1 font-semibold text-primary">

                            {{ number_format(
                                $statistics['volume'],
                                0,
                                ',',
                                '.'
                            ) }}

                            kg

                        </div>

                    </div>


                    {{-- Max weight --}}
                    <div
                        class="bg-surface p-4"
                    >

                        <div class="text-xs text-muted">

                            {{ __('Max Weight') }}

                        </div>


                        <div class="mt-1 font-semibold text-primary">

                            {{ number_format(
                                $statistics['max_weight'],
                                2,
                                ',',
                                '.'
                            ) }}

                            kg

                        </div>

                    </div>


                    {{-- Reps --}}
                    <div
                        class="bg-surface p-4"
                    >

                        <div class="text-xs text-muted">

                            {{ __('Reps') }}

                        </div>


                        <div class="mt-1 font-semibold text-primary">

                            {{ $statistics['total_reps'] }}

                        </div>

                    </div>


                    {{-- 1RM --}}
                    <div
                        class="bg-surface p-4"
                    >

                        <div class="text-xs text-muted">

                            {{ __('Estimated 1RM') }}

                        </div>


                        <div class="mt-1 font-semibold text-primary">

                            {{ number_format(
                                $statistics['estimated_1rm'],
                                1,
                                ',',
                                '.'
                            ) }}

                            kg

                        </div>

                    </div>

                </div>

            @endif


            {{-- Cardio --}}
            @if ($exercise->type->value === 'cardio')

                <div
                    class="grid grid-cols-2 gap-px bg-border"
                >

                    {{-- Duration --}}
                    @if ($statistics['duration'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Duration') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'duration',
                                    $statistics['duration']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Distance --}}
                    @if ($statistics['distance'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Distance') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'distance',
                                    $statistics['distance']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Calories --}}
                    @if ($statistics['calories'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Calories') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'calories',
                                    $statistics['calories']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Speed --}}
                    @if ($statistics['avg_speed'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Average Speed') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'speed',
                                    $statistics['avg_speed']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Pace --}}
                    @if ($statistics['pace'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Pace') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'pace',
                                    $statistics['pace']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Average heart rate --}}
                    @if ($statistics['avg_heart_rate'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Average Heart Rate') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'heart_rate',
                                    $statistics['avg_heart_rate']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Max heart rate --}}
                    @if ($statistics['max_heart_rate'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Max Heart Rate') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'heart_rate',
                                    $statistics['max_heart_rate']
                                ) }}

                            </div>

                        </div>

                    @endif


                    {{-- Watts --}}
                    @if ($statistics['avg_watts'] > 0)

                        <div class="bg-surface p-4">

                            <div class="text-xs text-muted">

                                {{ __('Average Watts') }}

                            </div>


                            <div
                                class="mt-1 font-semibold text-primary"
                            >

                                {{ $formatter->formatMetric(
                                    'watts',
                                    $statistics['avg_watts']
                                ) }}

                            </div>

                        </div>

                    @endif

                </div>

            @endif

        </div>

    @empty

        {{-- Empty state --}}
        <div
            class="flex min-h-64 flex-col items-center justify-center rounded-xl border border-border bg-surface p-8 text-center"
        >

            <x-lucide-history
                class="size-8 text-muted"
            />

            <p class="mt-3 text-sm text-muted">

                {{ __('No workout history yet.') }}

            </p>

        </div>

    @endforelse


    {{-- Load more --}}
    @if ($hasMore)

        <div class="flex justify-center pt-2">

            <button
                type="button"
                wire:click="loadMore"
                class="rounded-lg bg-surface-hover px-4 py-2 text-sm font-medium text-secondary transition hover:text-primary"
            >

                {{ __('Load more') }}

            </button>

        </div>

    @endif

</div>
