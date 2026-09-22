@use('App\Enum\ExerciseType')

@php
    $workoutName = $session->workout_name;

    $defaultRestSeconds = $session->workout
        ? $session->workout->workoutExercises
            ->flatMap->workoutExerciseSets
            ->whereNotNull('rest_seconds')
            ->first()?->rest_seconds ?? 60
        : 60;
@endphp

<x-app-layout :pageTitle="__('Workout')">
    <div class="min-h-screen pb-36 md:pb-28">

        @if(!$session->completed)
            <div class="sticky top-16 md:top-0 z-50 rounded-lg bg-secondary backdrop-blur">
                <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-3">
                    {{-- Workout info --}}
                    <div class="min-w-0">
                        <h1 class="truncate text-lg font-semibold text-primary">{{ $session->workout_name }}</h1>
                        <p class="text-sm text-primary">{{ __('Workout') }}</p>
                    </div>

                    {{-- Workout timer --}}
                    <div x-data="workoutTimer(
                            '{{ $session->started_at->toIso8601String() }}',
                            '{{ $session->completed_at?->toIso8601String() }}'
                        )"
                         x-init="start()"
                         class="ml-4 shrink-0 text-right">
                        <div x-text="formattedTime" class="font-mono text-xl font-semibold tabular-nums"></div>
                        <div class="text-xs text-primary">{{ __('Time') }}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Workout Content --}}
        <main class="mx-auto max-w-4xl space-y-4 px-4 py-6">
            @if($session->workout)

                {{-- Bestaande normale workout --}}
                @foreach($session->workout->workoutExercises as $workoutExercise)

                    @if($workoutExercise->exercise->type === ExerciseType::Strength)
                        <livewire:sessions.strength-exercise
                            :session="$session"
                            :workout-exercise="$workoutExercise"
                            :key="'strength-' . $workoutExercise->id"
                        />

                    @elseif($workoutExercise->exercise->type === ExerciseType::Cardio)
                        <livewire:sessions.cardio-exercise
                            :session="$session"
                            :workout-exercise="$workoutExercise"
                            :key="'cardio-' . $workoutExercise->id"
                        />
                    @endif

                @endforeach

            @else

                {{-- Free Training --}}
                <livewire:sessions.free-training-exercises
                    :session="$session"
                    :key="'free-training-' . $session->id"
                />

            @endif
        </main>

        {{-- End Workout --}}
        @if(!$session->completed)
            <div class="mx-auto mt-6 max-w-4xl px-4">
                <form method="POST"
                      action="{{ route('sessions.complete', $session) }}"
                      onsubmit="return confirm('Weet je zeker dat je deze workout wilt stoppen?')">
                    @csrf
                    @method('PATCH')

                    <x-button.default variant="primary"
                                      class="w-full">
                        {{ __('End workout') }}
                    </x-button.default>
                </form>
            </div>
        @endif
    </div>

    {{-- Rest Timer Bottom Bar --}}
    @if(!$session->completed)
        <div x-data="restTimer({{ (int) $defaultRestSeconds }})"
             x-init="init()"
             x-show="isVisible"
             x-cloak
             x-on:start-rest-timer.window="start($event.detail?.seconds ?? $event.detail)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed bottom-14 left-0 right-0 z-30 border-t border-border bg-surface/95 shadow-lg backdrop-blur-md md:bottom-0"
             :class="sidebarOpen ? 'md:left-48' : 'md:left-16 lg:left-48'">

            {{-- Progress Bar --}}
            <div class="h-1 w-full overflow-hidden bg-border/40">
                <div class=" h-full bg-secondary transition-all duration-300 ease-linear"
                     :style="'width: ' + progressPercent + '%'"></div>
            </div>


            {{-- Timer Content --}}
            <div class="
                    mx-auto
                    flex
                    max-w-4xl
                    items-center
                    justify-between
                    gap-2
                    px-4
                    py-2.5
                    sm:gap-4
                ">

                {{-- Left --}}
                <div class="flex min-w-0 items-center gap-2.5">
                    <div class=" shrink-0 rounded-lg bg-surface-hover p-2 text-secondary">
                        <x-lucide-timer class="size-5"/>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="truncate text-xs font-medium text-muted">
                                {{ __('Rest timer') }}
                            </span>

                            <template x-if="isFinished">
                                <span
                                    class="rounded bg-success/20 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-success">
                                    {{ __('Done') }}
                                </span>
                            </template>
                        </div>


                        <div class="flex items-baseline gap-1.5">
                            <span x-text="formattedRemaining"
                                  class="font-mono text-xl font-bold leading-none tabular-nums sm:text-2xl"
                                  :class="isFinished ? 'text-success animate-pulse' : (
                                          isRunning ? 'text-secondary' : 'text-primary')">

                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right / Controls --}}
                <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                    {{-- Minus 15 --}}
                    <button type="button"
                            @click="addSeconds(-15)"
                            class="rounded-md border border-border bg-surface px-2 py-1 text-xs font-semibold text-muted transition hover:bg-surface-hover hover:text-primary">
                        -15s
                    </button>

                    {{-- Play / Pause --}}
                    <button type="button"
                            @click="toggle()"
                            class="flex items-center justify-center rounded-full bg-secondary p-2 text-white shadow-sm transition hover:opacity-90">
                        <span x-show="isRunning"
                              class="flex items-center justify-center">
                            <x-lucide-pause class="size-4"/>
                        </span>

                        <span x-show="!isRunning"
                              class="flex items-center justify-center">
                            <x-lucide-play class="ml-0.5 size-4 fill-current"/>
                        </span>
                    </button>

                    {{-- Plus 30 --}}
                    <button type="button"
                            @click="addSeconds(30)"
                            class="rounded-md border border-border bg-surface px-2 py-1 text-xs font-semibold text-muted transition hover:bg-surface-hover hover:text-primary">
                        +30s
                    </button>

                    {{-- Reset --}}
                    <button type="button"
                            @click="reset()"
                            class=" rounded-md p-1.5 text-muted transition hover:bg-surface-hover hover:text-primary">
                        <x-lucide-rotate-ccw class="size-4"/>
                    </button>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
