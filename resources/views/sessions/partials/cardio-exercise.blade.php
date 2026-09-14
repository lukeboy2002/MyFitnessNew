<x-card.default
    title="{{ $workoutExercise->exercise->name }}"
    text_color="text-primary"
    text_size="text-base"
    font_weight="font-semibold"
    class="overflow-hidden"
>

    {{-- High score --}}
    <div class="flex justify-end items-center gap-1 mb-2">
        <x-lucide-trophy class="w-4 h-4 text-yellow-500"/>

        <div class="text-xs font-black text-info">
            {{ $highestScore ?? '-' }}
        </div>
    </div>


    {{--TEMPLATE SETS--}}
    <div class="border-t border-border/50">
        <div class="grid grid-cols-6 gap-2 px-4 py-2 text-xs text-muted">
            <div class="col-span-1">{{ __('Type') }}</div>
            <div class="col-span-1 text-center">{{ __('Duration') }}</div>
            <div class="col-span-3">{{ __('Intensity') }}</div>
            <div class="col-span-1 text-right">{{ __('Last') }}</div>
        </div>


        @foreach($workoutExercise->workoutExerciseSets as $templateSet)
            <div wire:key="cardio-template-set-{{ $templateSet->id }}"
                 class="grid grid-cols-6 items-center gap-2 border-t border-border/50 px-4 py-3">
                {{-- Type --}}
                <div class="col-span-1">
                    <div class="text-xs font-medium text-secondary">
                        {{ $templateSet->type->value }}
                    </div>
                </div>

                {{-- Duration --}}
                <div class="col-span-1">
                    <div class="text-center text-sm text-secondary">
                        @if($templateSet->target_duration_seconds)
                            {{ floor($templateSet->target_duration_seconds / 60) }}
                        @else
                            -
                        @endif
                        <span class="text-xs text-muted">
                            {{ __('min') }}
                        </span>
                    </div>
                </div>

                {{-- Intensity --}}
                <div class="col-span-3">
                    @if($templateSet->target_metric)
                        <div class="flex items-center gap-2 text-sm text-muted">
                            <span>
                                {{ ucfirst(str_replace('_', ' ', $templateSet->target_metric->value)) }}
                            </span>

                            @if($templateSet->target_metric_value !== null)
                                <span class="text-secondary">
                                    {{ $templateSet->target_metric_value }}
                                </span>
                            @endif
                        </div>
                    @else
                        <span class="text-sm text-muted">
                            -
                        </span>
                    @endif
                </div>


                {{-- Previous --}}
                <div class="col-span-1 text-right">
                    <div class="text-xs font-black text-info">
                        {{ $previousSessionSets[$templateSet->id] ?? '-' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ACTUAL RESULTS --}}
    <div x-data="{ open: false }"
         class="border-t border-border/50">
        {{-- Toggle --}}
        <button type="button"
                @click="open = !open"
                class="flex w-full items-center justify-between px-4 py-3 text-left">
            <span class="text-sm font-medium text-secondary">
                {{ __('Actual results') }}
            </span>
            <x-lucide-chevron-down
                class="h-4 w-4 text-secondary transition-transform"
                ::class="{ 'rotate-180': open }"
            />
        </button>

        {{-- Content --}}
        <div x-show="open"
             x-collapse
             class="px-4 pb-4">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                {{-- Duration --}}
                <div>
                    <x-form.label for="duration" :value="__('Duration')"/>
                    <x-form.input type="text"
                                  wire:model="duration"
                                  placeholder="20:00"
                                  :disabled="$completed"
                    />
                </div>
                {{-- Distance --}}
                <div>
                    <x-form.label for="distanceKm" :value="__('Distance (km)')"/>
                    <x-form.input type="number"
                                  wire:model="distanceKm"
                                  min="0"
                                  step="0.01"
                                  placeholder="3.52"
                                  :disabled="$completed"
                    />
                </div>


                {{-- Average speed --}}
                <div>

                    <x-form.label
                        for="avgSpeed"
                        :value="__('Avg. speed (km/h)')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="avgSpeed"
                        min="0"
                        step="0.01"
                        placeholder="10.50"
                        :disabled="$completed"
                    />

                </div>


                {{-- Pace --}}
                <div>

                    <x-form.label
                        for="pace"
                        :value="__('Pace (min/km)')"
                    />

                    <x-form.input
                        type="text"
                        wire:model="pace"
                        placeholder="10:00"
                        :disabled="$completed"
                    />

                </div>


                {{-- Incline --}}
                <div>

                    <x-form.label
                        for="inclineDegrees"
                        :value="__('Incline (%)')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="inclineDegrees"
                        min="0"
                        step="0.1"
                        placeholder="5"
                        :disabled="$completed"
                    />

                </div>


                {{-- Watts --}}
                <div>

                    <x-form.label
                        for="watts"
                        :value="__('Watts')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="watts"
                        min="0"
                        placeholder="120"
                        :disabled="$completed"
                    />

                </div>


                {{-- METs --}}
                <div>

                    <x-form.label
                        for="mets"
                        :value="__('METs')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="mets"
                        min="0"
                        step="0.1"
                        placeholder="6.8"
                        :disabled="$completed"
                    />

                </div>


                {{-- Active calories --}}
                <div>

                    <x-form.label
                        for="caloriesActive"
                        :value="__('Calories active')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="caloriesActive"
                        min="0"
                        placeholder="156"
                        :disabled="$completed"
                    />

                </div>


                {{-- Total calories --}}
                <div>

                    <x-form.label
                        for="caloriesTotal"
                        :value="__('Calories total')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="caloriesTotal"
                        min="0"
                        placeholder="185"
                        :disabled="$completed"
                    />

                </div>


                {{-- Average heart rate --}}
                <div>

                    <x-form.label
                        for="avgHeartRate"
                        :value="__('Avg. heart rate')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="avgHeartRate"
                        min="0"
                        placeholder="142"
                        :disabled="$completed"
                    />

                </div>

                {{-- Max heart rate --}}
                <div>

                    <x-form.label
                        for="maxHeartRate"
                        :value="__('Max. heart rate')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="maxHeartRate"
                        min="0"
                        placeholder="165"
                        :disabled="$completed"
                    />

                </div>

                {{-- Stroke rate --}}
                <div>

                    <x-form.label
                        for="strokeRate"
                        :value="__('Stroke rate')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="strokeRate"
                        min="0"
                        placeholder="30"
                        :disabled="$completed"
                    />

                </div>


                {{-- Rotations --}}
                <div>

                    <x-form.label
                        for="rotations"
                        :value="__('Rotations')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="rotations"
                        min="0"
                        placeholder="60"
                        :disabled="$completed"
                    />

                </div>


                {{-- Floors --}}
                <div>

                    <x-form.label
                        for="floors"
                        :value="__('Floors')"
                    />

                    <x-form.input
                        type="number"
                        wire:model="floors"
                        min="0"
                        placeholder="18"
                        :disabled="$completed"
                    />

                </div>

            </div>


            {{-- ====================================================
                 SPLITS
            ==================================================== --}}
            <div class="pt-6 text-sm font-medium text-muted">
                {{ __('Split times') }}
            </div>


            @foreach($splits as $splitNumber => $split)

                <div
                    wire:key="split-{{ $splitNumber }}"
                    class="grid grid-cols-12 items-center gap-2 border-b border-border/50 py-2"
                >

                    {{-- Number --}}
                    <div
                        class="col-span-2 pt-6 text-sm font-medium text-secondary"
                    >
                        {{ $splitNumber }}
                    </div>


                    {{-- Distance --}}
                    <div class="col-span-4">

                        <x-form.label
                            for="splits.{{ $splitNumber }}.distance"
                            :value="__('Distance (km)')"
                        />

                        <x-form.input
                            type="number"
                            wire:model="splits.{{ $splitNumber }}.distance"
                            wire:change="saveSplit({{ $splitNumber }})"
                            min="0"
                            step="0.01"
                            placeholder="1.00"
                            :disabled="$completed"
                        />

                    </div>


                    {{-- Duration --}}
                    <div class="col-span-4">

                        <x-form.label
                            for="splits.{{ $splitNumber }}.duration"
                            :value="__('Duration')"
                        />

                        <x-form.input
                            type="text"
                            wire:model="splits.{{ $splitNumber }}.duration"
                            wire:change="saveSplit({{ $splitNumber }})"
                            placeholder="5:42"
                            :disabled="$completed"
                        />

                    </div>


                    {{-- Remove --}}
                    <div
                        class="col-span-2 flex justify-end pt-6"
                    >

                        @unless($completed)

                            <x-button.danger
                                variant="ghost"
                                type="button"
                                wire:click="removeSplit({{ $splitNumber }})"
                            >

                                <x-lucide-trash-2
                                    class="h-4 w-4"
                                />

                            </x-button.danger>

                        @endunless

                    </div>

                </div>

            @endforeach


            {{-- Add split --}}
            @unless($completed)

                <div class="mt-3">

                    <button
                        type="button"
                        wire:click="addSplit"
                        class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-muted hover:bg-surface-hover"
                    >

                        <x-lucide-plus class="h-4 w-4"/>

                        {{ __('Add split') }}

                    </button>

                </div>

            @endunless


            {{-- Save --}}
            @unless($completed)

                <div class="mt-4 flex justify-end">

                    <x-button.default
                        variant="outline"
                        type="button"
                        wire:click="saveResults"
                    >

                        {{ __('Save results') }}

                    </x-button.default>

                </div>

            @endunless

        </div>

    </div>


    {{-- ============================================================
         COMPLETE EXERCISE
    ============================================================ --}}
    <div
        class="flex justify-end border-t border-border/50 px-4 py-3"
    >

        <button
            type="button"
            wire:click="completeExercise"
            @disabled($completed)
            class="flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-medium"
        >

            @if($completed)

                <div class="flex items-center gap-1 text-success">

                    <x-lucide-circle-check-big
                        class="h-5 w-5"
                    />

                    <span>
                        {{ __('Completed') }}
                    </span>

                </div>

            @else

                <div class="flex items-center gap-1 text-muted">

                    <x-lucide-circle-check-big
                        class="h-5 w-5"
                    />

                    <span>
                        {{ __('Complete') }}
                    </span>

                </div>

            @endif

        </button>

    </div>


    {{-- ============================================================
         HIGH SCORE MODAL
    ============================================================ --}}
    <x-modal
        name="congratulations-pr-cardio-{{ $workoutExercise->id }}"
        maxWidth="md"
    >

        <div class="p-6 text-center">

            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-500/10"
            >

                <x-lucide-trophy
                    class="h-10 w-10 text-yellow-500"
                />

            </div>


            <h2 class="text-xl font-bold text-primary">
                {{ __('Gefeliciteerd!') }}
            </h2>


            <p class="mt-2 text-sm text-muted">

                {{ __('Je hebt een nieuwe high score behaald op') }}

                <span class="font-semibold text-primary">
                    {{ $workoutExercise->exercise->name }}
                </span>

                !

            </p>


            <div
                class="my-4 inline-flex items-center gap-2 rounded-xl bg-info/10 px-4 py-2 text-lg font-black text-info"
            >

                <x-lucide-trophy
                    class="h-5 w-5 text-yellow-500"
                />

                <span>
                    {{ $newHighScoreValue ?? $highestScore }}
                </span>

            </div>


            <div class="mt-4">

                <x-button.default
                    variant="primary"
                    class="w-full"
                    x-on:click="$dispatch(
                        'close-modal',
                        'congratulations-pr-cardio-{{ $workoutExercise->id }}'
                    )"
                >

                    {{ __('Geweldig!') }}

                </x-button.default>

            </div>

        </div>

    </x-modal>

</x-card.default>
