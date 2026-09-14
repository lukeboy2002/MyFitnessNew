<x-modal name="add-exercise-modal" maxWidth="2xl" focusable>
    <div class="p-6">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-border">
            <div>
                <h3 class="text-lg font-semibold text-primary">
                    {{ __('Add Exercise') }}
                </h3>
                <p class="text-xs text-muted">
                    {{ __('Select an exercise to add to this workout.') }}
                </p>
            </div>

            <x-button.default
                variant="ghost"
                type="button"
                size="5"
                icon="x"
                x-on:click="$dispatch('close-modal', 'add-exercise-modal')"
            />
        </div>

        {{-- Filters --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
            {{-- Search --}}
            <div>
                <x-form.label for="searchExercise" :value="__('Search')"/>
                <x-form.input
                    id="searchExercise"
                    type="text"
                    wire:model.live.debounce.300ms="searchExercise"
                    placeholder="{{ __('Search by name...') }}"
                    class="mt-1 block w-full text-xs"
                />
            </div>

            {{-- Muscle Group --}}
            <div>
                <x-form.label for="filterMuscleGroup" :value="__('Filter by Muscle Group')"/>
                <x-form.select
                    id="filterMuscleGroup"
                    wire:model.live="filterMuscleGroup"
                    class="mt-1 block w-full text-xs"
                >
                    <option value="">{{ __('All Muscle Groups') }}</option>
                    @foreach ($all_muscle_groups as $mg)
                        <option value="{{ $mg->id }}">{{ $mg->name }}</option>
                    @endforeach
                </x-form.select>
            </div>
        </div>

        {{-- Results --}}
        <div class="max-h-96 overflow-y-auto space-y-2 pr-1 divide-y divide-border/40">
            @forelse ($available_exercises as $ex)
                @php
                    $isAdded = in_array($ex->id, $already_added_exercise_ids, true);
                @endphp

                <div
                    wire:key="available-exercise-{{ $ex->id }}"
                    class="flex items-center justify-between py-2.5 px-2 rounded-md hover:bg-surface-hover/60 transition gap-3"
                >
                    <div class="flex items-center gap-3">
                        {{-- Image --}}
                        @if ($ex->image_path)
                            <img
                                src="{{ asset('storage/' . $ex->image_path) }}"
                                alt="{{ $ex->name }}"
                                class="w-10 h-10 rounded-lg object-cover border border-border bg-surface shrink-0"
                            >
                        @else
                            <div
                                class="w-10 h-10 rounded-lg border border-border bg-surface-hover flex items-center justify-center text-muted shrink-0">
                                <x-lucide-dumbbell class="w-5 h-5"/>
                            </div>
                        @endif

                        {{-- Info --}}
                        <div>
                            <div class="font-medium text-primary text-sm">
                                {{ $ex->name }}
                            </div>

                            <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                <span
                                    class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-secondary/15 text-secondary capitalize">
                                    {{ $ex->type->value }}
                                </span>

                                @foreach ($ex->muscleGroups as $mg)
                                    <span class="text-[10px] text-muted">
                                        {{ $mg->name }}@if (!$loop->last)
                                            •
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Add Action --}}
                    <div>
                        @if ($isAdded)
                            <span
                                class="inline-flex items-center gap-1 text-xs text-muted font-medium py-1.5 px-3 rounded-md bg-surface border border-border">
                                <x-lucide-check class="w-3.5 h-3.5 text-secondary"/>
                                {{ __('Added') }}
                            </span>
                        @else
                            <x-button.default
                                type="button"
                                variant="primary"
                                size="3"
                                icon="plus"
                                wire:click="addExercise({{ $ex->id }})"
                            >
                                {{ __('Add') }}
                            </x-button.default>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-muted text-sm">
                    <x-lucide-search class="w-8 h-8 mx-auto mb-2 opacity-50"/>
                    {{ __('No exercises found matching your criteria.') }}
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end border-t border-border pt-4">
            <x-button.default
                variant="outline"
                type="button"
                x-on:click="$dispatch('close-modal', 'add-exercise-modal')"
                class="w-full sm:w-auto"
            >
                {{ __('Close') }}
            </x-button.default>
        </div>
    </div>
</x-modal>
