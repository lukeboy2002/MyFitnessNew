<div class="flex flex-col gap-6">

    @if ($sets->isEmpty())

        <div
            class="rounded-xl border border-border bg-surface p-8 text-center"
        >

            <x-lucide-dumbbell
                class="mx-auto size-10 text-muted"
            />

            <p class="mt-4 text-sm text-muted">
                No completed workouts yet.
            </p>

        </div>

    @else

        {{-- ========================================= --}}
        {{-- STRENGTH --}}
        {{-- ========================================= --}}

        @if ($isStrength)

            <div
                class="grid grid-cols-2 gap-4 lg:grid-cols-4"
            >

                {{-- Sessions --}}
                <x-card.default>

                    <p class="text-xs text-muted">
                        Sessions
                    </p>

                    <p
                        class="mt-2 text-xl font-semibold text-primary"
                    >
                        {{ $statistics['sessions'] }}
                    </p>

                </x-card.default>


                {{-- Volume --}}
                <x-card.default>

                    <p class="text-xs text-muted">
                        Total Volume
                    </p>

                    <p
                        class="mt-2 text-xl font-semibold text-primary"
                    >
                        {{ $formatter->formatMetric(
                            'volume',
                            $statistics['volume']
                        ) }}
                    </p>

                </x-card.default>


                {{-- Max weight --}}
                <x-card.default>

                    <p class="text-xs text-muted">
                        Max Weight
                    </p>

                    <p
                        class="mt-2 text-xl font-semibold text-primary"
                    >
                        {{ $formatter->formatMetric(
                            'weight',
                            $statistics['max_weight']
                        ) }}
                    </p>

                </x-card.default>


                {{-- 1RM --}}
                <x-card.default>

                    <p class="text-xs text-muted">
                        Best 1RM
                    </p>

                    <p
                        class="mt-2 text-xl font-semibold text-primary"
                    >
                        {{ $formatter->formatMetric(
                            'one_rep_max',
                            $statistics['one_rep_max']
                        ) }}
                    </p>

                </x-card.default>

            </div>


            {{-- More strength statistics --}}
            <div
                class="grid grid-cols-2 gap-4"
            >

                <x-card.default>

                    <p class="text-xs text-muted">
                        Total Sets
                    </p>

                    <p
                        class="mt-2 text-lg font-semibold text-primary"
                    >
                        {{ $statistics['total_sets'] }}
                    </p>

                </x-card.default>


                <x-card.default>

                    <p class="text-xs text-muted">
                        Total Reps
                    </p>

                    <p
                        class="mt-2 text-lg font-semibold text-primary"
                    >
                        {{ $statistics['total_reps'] }}
                    </p>

                </x-card.default>

            </div>

        @endif


        {{-- ========================================= --}}
        {{-- CARDIO --}}
        {{-- ========================================= --}}

        @if (! $isStrength)

            <div
                class="grid grid-cols-2 gap-4 lg:grid-cols-4"
            >

                {{-- Sessions --}}
                <x-card.default>

                    <p class="text-xs text-muted">
                        Sessions
                    </p>

                    <p
                        class="mt-2 text-xl font-semibold text-primary"
                    >
                        {{ $statistics['sessions'] }}
                    </p>

                </x-card.default>


                {{-- Distance --}}
                @if ($statistics['distance'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Total Distance
                        </p>

                        <p
                            class="mt-2 text-xl font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'distance',
                                $statistics['distance']
                            ) }}
                        </p>

                    </x-card.default>

                @endif


                {{-- Duration --}}
                @if ($statistics['duration'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Total Duration
                        </p>

                        <p
                            class="mt-2 text-xl font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'duration',
                                $statistics['duration']
                            ) }}
                        </p>

                    </x-card.default>

                @endif


                {{-- Calories --}}
                @if ($statistics['calories'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Total Calories
                        </p>

                        <p
                            class="mt-2 text-xl font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'calories',
                                $statistics['calories']
                            ) }}
                        </p>

                    </x-card.default>

                @endif

            </div>


            {{-- Cardio details --}}
            <div
                class="grid grid-cols-2 gap-4 lg:grid-cols-4"
            >

                {{-- Speed --}}
                @if ($statistics['avg_speed'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Average Speed
                        </p>

                        <p
                            class="mt-2 text-lg font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'speed',
                                $statistics['avg_speed']
                            ) }}
                        </p>

                    </x-card.default>

                @endif


                {{-- Average heart rate --}}
                @if ($statistics['avg_heart_rate'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Avg Heart Rate
                        </p>

                        <p
                            class="mt-2 text-lg font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'heart_rate',
                                $statistics['avg_heart_rate']
                            ) }}
                        </p>

                    </x-card.default>

                @endif


                {{-- Max heart rate --}}
                @if ($statistics['max_heart_rate'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Max Heart Rate
                        </p>

                        <p
                            class="mt-2 text-lg font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'heart_rate',
                                $statistics['max_heart_rate']
                            ) }}
                        </p>

                    </x-card.default>

                @endif


                {{-- Watts --}}
                @if ($statistics['avg_watts'] > 0)

                    <x-card.default>

                        <p class="text-xs text-muted">
                            Avg Watts
                        </p>

                        <p
                            class="mt-2 text-lg font-semibold text-primary"
                        >
                            {{ $formatter->formatMetric(
                                'watts',
                                $statistics['avg_watts']
                            ) }}
                        </p>

                    </x-card.default>

                @endif

            </div>

        @endif


        {{-- ========================================= --}}
        {{-- LATEST WORKOUT --}}
        {{-- ========================================= --}}

        @if ($latestSession)

            <x-card.default
                title="Latest Workout"
            >

                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >

                    <div>

                        <p
                            class="font-medium text-primary"
                        >
                            {{ $latestSession['session']->workout->name }}
                        </p>

                        <p
                            class="text-sm text-muted"
                        >
                            {{
                                $latestSession['session']
                                    ->completed_at
                                    ?->format('d M Y H:i')
                            }}
                        </p>

                    </div>


                    <div
                        class="text-sm text-muted"
                    >

                        {{ $latestSession['sets']->count() }}

                        {{
                            Str::plural(
                                'set',
                                $latestSession['sets']->count()
                            )
                        }}

                    </div>

                </div>

            </x-card.default>

        @endif

    @endif

</div>
