<div class="px-4 pt-6">
    <x-card variant="ghost" title="{{ __('Workouts') }}" description="{{ __('All your workouts') }}">

        <x-slot:actions>
            <div class=" hidden md:flex items-center justify-end pb-1 mb-2 gap-2
    ">
                <x-link.default variant="outline"
                                icon="square-plus"
                                size="3"
                                href="{{ route('workout.create') }}"
                />
            </div>
        </x-slot:actions>
        <div class="mt-4">
            {{-- Actieve workouts --}}
            @if ($active->isNotEmpty())
                <div class="space-y-3 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($active as $workout)
                            <x-card.default>
                                <div>
                                    <div class="flex gap-4 pb-4">
                                        <x-lucide-layers class="w-12 h-12 text-secondary"/>

                                        <form method="POST" action="{{ route('sessions.start') }}">
                                            @csrf
                                            <input type="hidden" name="workout_id" value="{{ $workout->id }}">

                                            <button type="submit"
                                                    class="flex justify-between items-center w-full text-left">
                                                <div class="flex flex-col space-y-2 w-full">
                                                    <div class="flex items-center justify-between">
                                                        <div
                                                            class="font-medium font-theme text-primary text-base flex items-center gap-2">
                                                            {{ $workout->name }}
                                                            <x-lucide-play class="w-4 h-4"/>
                                                        </div>
                                                    </div>

                                                    <div class="text-xs text-muted">
                                                        {{ $workout->workout_exercises_count }} {{ __('exercises') }}
                                                    </div>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="flex border-t border-border divide-x divide-border -mx-4 -mb-4">
                                        <x-button.default variant="ghost"
                                                          type="button"
                                                          class="flex-1 py-2 text-xs text-primary hover:rounded-bl-lg hover:bg-surface-hover hover:text-secondary transition"
                                                          wire:click="showDetails({{ $workout->id }})">
                                            {{ __('Details') }}
                                        </x-button.default>
                                        <a href="{{ route('workout.edit', $workout) }}"
                                           class="flex-1 text-center py-2 text-xs text-primary hover:bg-surface-hover hover:text-secondary transition">
                                            {{ __('Edit') }}
                                        </a>
                                        <x-button.default variant="ghost"
                                                          type="button"
                                                          class="flex-1 py-2 text-xs text-primary hover:rounded-br-lg hover:bg-surface-hover hover:text-secondary transition"
                                                          wire:click="confirmToggleArchive({{ $workout->id }})">
                                            {{ __('Archive') }}
                                        </x-button.default>
                                    </div>
                                </div>
                            </x-card.default>
                        @endforeach
                    </div>
                </div>
                <x-link.default variant="primary" href="{{ route('workout.create') }}" class="w-full md:hidden">
                    {{ __('Create workout') }}
                </x-link.default>
            @else
                <x-card.default class="p-4">
                    <x-lucide-layers-2 class="w-16 h-16 text-secondary text-center mx-auto mb-3"/>
                    <p class="text-muted text-sm mb-3">{{ __('You don\'t have any workouts yet') }}</p>
                    <x-link.default variant="primary" href="{{ route('workout.create') }}">
                        {{ __('Create workout') }}
                    </x-link.default>
                </x-card.default>
            @endif

            {{-- Archief --}}
            <div class="pt-4">
                @if ($archived->isNotEmpty())
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-secondary
                       hover:text-primary transition mb-3">
                            <span x-text="open ? '▼' : '▶'"></span>
                            Archief ({{ $archived->count() }})
                        </button>

                        <div x-show="open" x-transition class="space-y-2">
                            @foreach ($archived as $workout)
                                <x-card.default class="p-4">
                                    <div class="flex justify-between items-center">
                                        <div class="flex flex-col space-y-2">
                                            <div
                                                class="font-medium font-theme text-primary text-base">{{ $workout->name }}</div>
                                            <div class="text-xs text-muted">
                                                {{ $workout->workout_exercises_count }} {{ __('exercises') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    wire:click="showDetails({{ $workout->id }})"
                                                    class="text-xs text-primary hover:underline">
                                                {{ __('Details') }}
                                            </button>
                                            <button type="button"
                                                    wire:click="confirmToggleArchive({{ $workout->id }}, true)"
                                                    class="text-xs text-secondary hover:underline">
                                                {{ __('Restore') }}
                                            </button>
                                        </div>
                                    </div>
                                </x-card.default>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    <x-modal name="confirm-workout-archive" focusable>
        <form wire:submit="toggleArchive" class="p-6">
            <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                <h2 class="text-xl font-semibold text-secondary">
                    {{ $isUnarchiving ? __('Restore Workout') : __('Archive Workout') }}
                </h2>
                <x-button.default variant="ghost"
                                  type="button"
                                  size="5"
                                  icon="x"
                                  x-on:click="$dispatch('close-modal', 'confirm-workout-archive')"
                />
            </div>

            <div class="py-4">
                <div class="flex justify-center mb-4 text-error">
                    <x-lucide-circle-alert class="h-12 w-12"/>
                </div>
                <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                    {{ $isUnarchiving
                        ? __('Are you sure you want to restore this workout?')
                        : __('Are you sure you want to archive this workout?') }}
                </h3>
                @if($workoutToArchive)
                    <p class="text-center font-bold text-secondary">{{ $workoutToArchive->name }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                <x-button.default variant="outline"
                                  type="button"
                                  x-on:click="$dispatch('close-modal', 'confirm-workout-archive')"
                                  class="w-full"
                >
                    {{ __('No') }}
                </x-button.default>
                <x-button.default variant="primary"
                                  class="w-full"
                >
                    {{ __('Yes') }}
                </x-button.default>
            </div>
        </form>
    </x-modal>
    <x-modal name="detail-workout" maxWidth="2xl" focusable>
        @if ($detailWorkout)
            <div class="p-6">
                {{-- Header --}}
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-border">
                    <div>
                        <h2 class="text-xl font-semibold text-secondary">
                            {{ $detailWorkout->name }}
                        </h2>
                        <p class="text-xs text-muted">
                            {{ $detailWorkout->workoutExercises->count() }} {{ __('exercises') }}
                        </p>
                    </div>
                    <x-button.default variant="ghost"
                                      type="button"
                                      size="5"
                                      icon="x"
                                      x-on:click="$dispatch('close-modal', 'detail-workout')"
                    />
                </div>

                {{-- Exercises List --}}
                <div class="max-h-[70vh] overflow-y-auto space-y-4 pr-1">
                    @forelse ($detailWorkout->workoutExercises as $we)
                        <div class="border border-border rounded-lg bg-surface/50 p-4"
                             wire:key="detail-we-{{ $we->id }}">
                            {{-- Exercise Header --}}
                            <div class="flex items-center gap-3 pb-3 border-b border-border/60">
                                <span
                                    class="flex items-center justify-center w-7 h-7 rounded-full bg-secondary/15 text-secondary text-xs font-bold shrink-0">
                                    {{ $we->order }}
                                </span>

                                @if ($we->exercise->image_path)
                                    <img
                                        src="{{ asset('storage/' . $we->exercise->image_path) }}"
                                        alt="{{ $we->exercise->name }}"
                                        class="w-10 h-10 rounded-lg object-cover border border-border bg-surface shrink-0"
                                    >
                                @else
                                    <div
                                        class="w-10 h-10 rounded-lg border border-border bg-surface-hover flex items-center justify-center text-muted shrink-0">
                                        <x-lucide-dumbbell class="w-5 h-5"/>
                                    </div>
                                @endif

                                <div>
                                    <h4 class="font-semibold text-primary text-sm leading-tight">
                                        {{ $we->exercise->name }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-1 mt-1">
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-medium bg-secondary/10 text-secondary capitalize">
                                            {{ $we->exercise->type->value }}
                                        </span>
                                        @foreach ($we->exercise->bodyParts as $bp)
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-medium bg-surface-hover text-muted">
                                                {{ $bp->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if ($we->notes)
                                <div class="mt-2 text-xs text-muted bg-surface-hover/60 rounded p-2 italic">
                                    {{ $we->notes }}
                                </div>
                            @endif

                            {{-- Sets Section --}}
                            <div class="mt-3">
                                <div class="text-xs font-semibold uppercase tracking-wider text-muted mb-2">
                                    {{ __('Sets') }} ({{ $we->workoutExerciseSets->count() }})
                                </div>

                                @if ($we->workoutExerciseSets->isNotEmpty())
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-xs text-left">
                                            <thead class="text-muted border-b border-border/60">
                                            <tr>
                                                <th class="py-1 px-2 font-medium w-12">{{ __('Set') }}</th>
                                                <th class="py-1 px-2 font-medium w-24">{{ __('Type') }}</th>
                                                @if ($we->exercise->type->value === 'cardio')
                                                    <th class="py-1 px-2 font-medium">{{ __('Duration') }}</th>
                                                    <th class="py-1 px-2 font-medium">{{ __('Distance') }}</th>
                                                @else
                                                    <th class="py-1 px-2 font-medium">{{ __('Weight') }}</th>
                                                    <th class="py-1 px-2 font-medium">{{ __('Reps') }}</th>
                                                @endif
                                                <th class="py-1 px-2 font-medium">{{ __('Rest') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-border/40">
                                            @foreach ($we->workoutExerciseSets as $set)
                                                <tr wire:key="detail-set-{{ $set->id }}">
                                                    <td class="py-1.5 px-2 font-semibold text-secondary">
                                                        {{ $set->set_number }}
                                                    </td>
                                                    <td class="py-1.5 px-2">
                                                            <span class="capitalize text-muted">
                                                                {{ $set->type->value }}
                                                            </span>
                                                    </td>
                                                    @if ($we->exercise->type->value === 'cardio')
                                                        <td class="py-1.5 px-2 text-muted">
                                                            {{ $set->target_duration_seconds ? ($set->target_duration_seconds >= 60 ? round($set->target_duration_seconds / 60) . ' min' : $set->target_duration_seconds . ' s') : '-' }}
                                                        </td>
                                                        <td class="py-1.5 px-2 text-muted">
                                                            {{ $set->target_distance_km ? (float) $set->target_distance_km . ' km' : '-' }}
                                                        </td>
                                                    @else
                                                        <td class="py-1.5 px-2 text-muted">
                                                            {{ $set->target_weight !== null ? (float) $set->target_weight . ' kg' : '-' }}
                                                        </td>
                                                        <td class="py-1.5 px-2 text-muted">
                                                            {{ $set->target_reps !== null ? $set->target_reps : '-' }}
                                                        </td>
                                                    @endif
                                                    <td class="py-1.5 px-2 text-muted">
                                                        {{ $set->rest_seconds ? $set->rest_seconds . ' s' : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-xs text-muted italic">{{ __('No sets configured for this exercise.') }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center border-2 border-dashed border-border rounded-lg">
                            <x-lucide-dumbbell class="w-10 h-10 text-secondary mx-auto mb-2 opacity-60"/>
                            <p class="text-sm font-medium text-primary mb-1">{{ __('No exercises in this workout') }}</p>
                            <p class="text-xs text-muted mb-3">{{ __('You can add exercises by editing this workout.') }}</p>
                            <x-link.default variant="primary" size="3"
                                            href="{{ route('workout.edit', $detailWorkout) }}">
                                {{ __('Edit workout') }}
                            </x-link.default>
                        </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                    <x-button.default variant="outline"
                                      type="button"
                                      x-on:click="$dispatch('close-modal', 'detail-workout')"
                                      class="w-full"
                    >
                        {{ __('Close') }}
                    </x-button.default>
                </div>
            </div>
        @endif
    </x-modal>
</div>
