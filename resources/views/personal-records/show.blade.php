<x-app-layout pageTitle="{{ $exercise->name }}">

    <div class="mb-6">

        {{-- Back button --}}
        <div class="mb-6">

            <x-link.default
                href="{{ route('personal-records.index') }}"
                variant="outline"
                icon="arrow-left"
            >
                {{ __('Personal Records') }}
            </x-link.default>

        </div>


        {{-- Header --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >

            <div class="flex items-center gap-4">

                {{-- Exercise Image --}}
                <div
                    class="w-16 h-16 rounded-2xl bg-surface-secondary border border-border overflow-hidden flex items-center justify-center shrink-0"
                >

                    @if ($exercise->image_path)

                        <img
                            src="{{ Storage::url($exercise->image_path) }}"
                            alt="{{ $exercise->name }}"
                            class="w-full h-full object-cover"
                        >

                    @else

                        <x-lucide-dumbbell
                            class="w-7 h-7 text-secondary"
                        />

                    @endif

                </div>


                <div>

                    <div class="flex items-center gap-2">

                        <x-lucide-trophy
                            class="w-6 h-6 text-secondary"
                        />

                        <span class="text-sm text-muted">
                            {{ __('Personal Records') }}
                        </span>

                    </div>


                    <h1
                        class="text-2xl font-bold text-primary mt-1"
                    >
                        {{ $exercise->name }}
                    </h1>


                    <div
                        class="flex items-center gap-2 mt-1"
                    >

                        @if (
                            $exercise->type->value === 'strength'
                        )

                            <x-lucide-dumbbell
                                class="w-4 h-4 text-secondary"
                            />

                            <span class="text-sm text-muted">
                                {{ __('Strength exercise') }}
                            </span>

                        @else

                            <x-lucide-heart-pulse
                                class="w-4 h-4 text-secondary"
                            />

                            <span class="text-sm text-muted">
                                {{ __('Cardio exercise') }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- High Scores --}}
        <div class="mt-6">

            <x-card.default
                variant="outline"
                class="w-full"
            >

                <div class="flex items-center gap-2 mb-5">

                    <x-lucide-trophy
                        class="w-5 h-5 text-secondary"
                    />

                    <div>

                        <h2 class="font-semibold text-primary">
                            {{ __('Personal Bests') }}
                        </h2>

                        <p class="text-sm text-muted">
                            {{ __('Your best performances for this exercise.') }}
                        </p>

                    </div>

                </div>


                <livewire:exercises.high-scores
                    :exercise="$exercise"
                    :key="'high-scores-'.$exercise->id"
                />

            </x-card.default>

        </div>


        {{-- Progress --}}
        <div class="mt-6">

            <x-card.default
                variant="outline"
                class="w-full"
            >

                <div class="flex items-center gap-2 mb-5">

                    <x-lucide-chart-no-axes-combined
                        class="w-5 h-5 text-secondary"
                    />

                    <div>

                        <h2 class="font-semibold text-primary">
                            {{ __('Progress') }}
                        </h2>

                        <p class="text-sm text-muted">
                            {{ __('Track your progress over time.') }}
                        </p>

                    </div>

                </div>


                <livewire:exercises.progress-chart
                    :exercise="$exercise"
                    :key="'progress-chart-'.$exercise->id"
                />

            </x-card.default>

        </div>


        {{-- History --}}
        <div class="mt-6">

            <x-card.default
                variant="outline"
                class="w-full"
            >

                <div class="flex items-center gap-2 mb-5">

                    <x-lucide-history
                        class="w-5 h-5 text-secondary"
                    />

                    <div>

                        <h2 class="font-semibold text-primary">
                            {{ __('History') }}
                        </h2>

                        <p class="text-sm text-muted">
                            {{ __('Your completed exercise history.') }}
                        </p>

                    </div>

                </div>


                <livewire:exercises.history
                    :exercise="$exercise"
                    :key="'exercise-history-'.$exercise->id"
                />

            </x-card.default>

        </div>

    </div>

</x-app-layout>
