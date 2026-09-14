<x-app-layout pageTitle="Personal Records">

    <div class="mb-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">

            <div
                class="w-12 h-12 rounded-2xl bg-secondary/10 flex items-center justify-center"
            >
                <x-lucide-trophy
                    class="w-6 h-6 text-secondary"
                />
            </div>

            <div>

                <h1 class="text-2xl font-bold text-primary">
                    {{ __('Personal Records') }}
                </h1>

                <p class="text-sm text-muted mt-1">
                    {{ __('Your best performances for each exercise.') }}
                </p>

            </div>

        </div>


        {{-- Empty state --}}
        @if ($personalRecords->isEmpty())

            <x-card.default
                variant="outline"
                class="w-full mt-6"
            >

                <div class="py-10 text-center">

                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-surface-secondary flex items-center justify-center mb-4"
                    >
                        <x-lucide-trophy
                            class="w-7 h-7 text-muted"
                        />
                    </div>

                    <h2 class="font-semibold text-primary">
                        {{ __('No personal records yet') }}
                    </h2>

                    <p class="text-sm text-muted mt-2">
                        {{ __('Complete a workout to start tracking your personal records.') }}
                    </p>

                </div>

            </x-card.default>

        @else

            {{-- Records --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

                @foreach ($personalRecords as $record)

                    @php
                        $exercise = $record['exercise'];
                    @endphp


                    <a
                        href="{{ route('personal-records.show', $exercise) }}"
                        class="block group"
                    >

                        <x-card.default
                            variant="outline"
                            class="w-full h-full transition group-hover:border-secondary group-hover:bg-surface-secondary/50"
                        >

                            {{-- Header --}}
                            <div class="flex items-center gap-4">

                                {{-- Image --}}
                                <div
                                    class="w-14 h-14 rounded-xl bg-surface-secondary border border-border overflow-hidden flex items-center justify-center shrink-0"
                                >

                                    @if ($exercise->image_path)

                                        <img
                                            src="{{ Storage::url($exercise->image_path) }}"
                                            alt="{{ $exercise->name }}"
                                            class="w-full h-full object-cover"
                                        >

                                    @else

                                        @if ($exercise->type->value === 'strength')

                                            <x-lucide-dumbbell
                                                class="w-6 h-6 text-secondary"
                                            />

                                        @else

                                            <x-lucide-heart-pulse
                                                class="w-6 h-6 text-secondary"
                                            />

                                        @endif

                                    @endif

                                </div>


                                {{-- Exercise --}}
                                <div class="min-w-0 flex-1">

                                    <div class="flex items-center justify-between gap-3">

                                        <h2
                                            class="font-semibold text-primary truncate group-hover:text-secondary transition"
                                        >
                                            {{ $exercise->name }}
                                        </h2>


                                        <x-lucide-chevron-right
                                            class="w-5 h-5 text-muted group-hover:text-secondary transition shrink-0"
                                        />

                                    </div>


                                    <p class="text-sm text-muted mt-1">

                                        @if ($exercise->type->value === 'strength')

                                            {{ __('Strength exercise') }}

                                        @else

                                            {{ __('Cardio exercise') }}

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- Strength records --}}
                            @if ($exercise->type->value === 'strength')

                                <div
                                    class="grid grid-cols-2 gap-3 mt-5"
                                >

                                    {{-- Highest Weight --}}
                                    <div
                                        class="bg-surface-secondary rounded-xl p-3"
                                    >

                                        <div class="flex items-center gap-2">

                                            <x-lucide-weight
                                                class="w-4 h-4 text-secondary"
                                            />

                                            <span class="text-xs text-muted">
                                                {{ __('Highest Weight') }}
                                            </span>

                                        </div>


                                        <p
                                            class="text-lg font-bold text-primary mt-2"
                                        >

                                            @if ($record['highest_weight'])

                                                {{ number_format(
                                                    (float) $record['highest_weight']->weight,
                                                    1
                                                ) }}

                                                <span class="text-sm font-normal text-muted">
                                                    kg
                                                </span>

                                            @else

                                                —

                                            @endif

                                        </p>

                                    </div>


                                    {{-- Most Reps --}}
                                    <div
                                        class="bg-surface-secondary rounded-xl p-3"
                                    >

                                        <div class="flex items-center gap-2">

                                            <x-lucide-repeat-2
                                                class="w-4 h-4 text-secondary"
                                            />

                                            <span class="text-xs text-muted">
                                                {{ __('Most Reps') }}
                                            </span>

                                        </div>


                                        <p
                                            class="text-lg font-bold text-primary mt-2"
                                        >

                                            @if ($record['most_reps'])

                                                {{ $record['most_reps']->reps }}

                                                <span class="text-sm font-normal text-muted">
                                                    reps
                                                </span>

                                            @else

                                                —

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            @endif


                            {{-- Cardio records --}}
                            @if ($exercise->type->value === 'cardio')

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5"
                                >

                                    {{-- Duration --}}
                                    <div
                                        class="bg-surface-secondary rounded-xl p-3"
                                    >

                                        <div class="flex items-center gap-2">

                                            <x-lucide-clock-3
                                                class="w-4 h-4 text-secondary"
                                            />

                                            <span class="text-xs text-muted">
                                                {{ __('Duration') }}
                                            </span>

                                        </div>


                                        <p
                                            class="text-lg font-bold text-primary mt-2"
                                        >

                                            @if ($record['longest_duration'])

                                                @php
                                                    $seconds = $record['longest_duration']->duration_seconds;
                                                    $hours = floor($seconds / 3600);
                                                    $minutes = floor(($seconds % 3600) / 60);
                                                @endphp

                                                @if ($hours > 0)

                                                    {{ $hours }}h {{ $minutes }}m

                                                @else

                                                    {{ $minutes }} min

                                                @endif

                                            @else

                                                —

                                            @endif

                                        </p>

                                    </div>


                                    {{-- Distance --}}
                                    <div
                                        class="bg-surface-secondary rounded-xl p-3"
                                    >

                                        <div class="flex items-center gap-2">

                                            <x-lucide-route
                                                class="w-4 h-4 text-secondary"
                                            />

                                            <span class="text-xs text-muted">
                                                {{ __('Distance') }}
                                            </span>

                                        </div>


                                        <p
                                            class="text-lg font-bold text-primary mt-2"
                                        >

                                            @if ($record['longest_distance'])

                                                {{ number_format(
                                                    (float) $record['longest_distance']->distance_km,
                                                    2
                                                ) }}

                                                <span class="text-sm font-normal text-muted">
                                                    km
                                                </span>

                                            @else

                                                —

                                            @endif

                                        </p>

                                    </div>


                                    {{-- Calories --}}
                                    <div
                                        class="bg-surface-secondary rounded-xl p-3"
                                    >

                                        <div class="flex items-center gap-2">

                                            <x-lucide-flame
                                                class="w-4 h-4 text-secondary"
                                            />

                                            <span class="text-xs text-muted">
                                                {{ __('Calories') }}
                                            </span>

                                        </div>


                                        <p
                                            class="text-lg font-bold text-primary mt-2"
                                        >

                                            @if ($record['most_calories'])

                                                {{ $record['most_calories']->calories_total }}

                                                <span class="text-sm font-normal text-muted">
                                                    kcal
                                                </span>

                                            @else

                                                —

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            @endif


                            {{-- View details --}}
                            <div
                                class="flex items-center gap-2 mt-5 text-sm text-secondary"
                            >

                                <x-lucide-chart-no-axes-combined
                                    class="w-4 h-4"
                                />

                                <span>
                                    {{ __('View progress and history') }}
                                </span>

                            </div>

                        </x-card.default>

                    </a>

                @endforeach

            </div>

        @endif

    </div>

</x-app-layout>
