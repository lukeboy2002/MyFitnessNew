<x-card.default title="{{ $workoutExercise->exercise->name }}" text_color="text-primary" text_size="text-base"
                font_weight="font-semibold" class="overflow-hidden">
    <div class="flex justify-end items-center gap-1 mb-2">
        <x-lucide-trophy class="w-4 h-4 text-yellow-500"/>
        <div class="text-xs font-black text-info">
            {{ $highestScore !== null ? $highestScore . ' kg' : '-' }}
        </div>
    </div>


    <div class="border-t border-border/50">

        <div class="grid grid-cols-6 gap-2 px-4 py-2 text-xs text-muted">
            <div>Set</div>
            <div>Type</div>
            <div>Gewicht</div>
            <div>Reps</div>
            <div></div>
            <div>Last</div>
        </div>

        @foreach($workoutExercise->workoutExerciseSets as $templateSet)
            <div wire:key="set-{{ $templateSet->id }}"
                 class="grid grid-cols-6 items-center gap-2 border-t px-4 py-3 border-border/50"
            >
                <div class="text-xs font-medium text-secondary">
                    {{ $templateSet->set_number }}
                </div>
                <div>
                    <div class="text-xs text-muted">{{ $templateSet->type->value ?? $templateSet->type }}</div>
                </div>
                <input
                    type="number"
                    wire:model="sets.{{ $templateSet->id }}.weight"
                    step="0.5"
                    placeholder="kg"
                    @if($sets[$templateSet->id]['completed']) disabled @endif
                    class="inline-flex px-3 text-sm text-muted placeholder-muted bg-transparent border border-border rounded-md"
                />

                <input
                    type="number"
                    wire:model="sets.{{ $templateSet->id }}.reps"
                    placeholder="reps"
                    @if($sets[$templateSet->id]['completed']) disabled @endif
                    class="inline-flex px-3 text-sm text-muted placeholder-muted bg-transparent border border-border rounded-md"
                />

                <button type="button"
                        wire:click="completeSet({{ $templateSet->id }})"
                        @disabled($sets[$templateSet->id]['completed'])
                        class="rounded-lg p-2"
                >
                    @if($sets[$templateSet->id]['completed'])
                        <x-lucide-circle-check-big class="h-5 w-5 text-success"/>
                    @else
                        <x-lucide-circle-check-big class="h-5 w-5 text-muted/50"/>
                    @endif
                </button>
                <div class="text-xs font-black text-info">
                    {{ $previousSessionSets[$templateSet->id] ?? '-' }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Congratulations PR Modal --}}
    <x-modal name="congratulations-pr-strength-{{ $workoutExercise->id }}" maxWidth="md">
        <div class="p-6 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-500/10 mb-4">
                <x-lucide-trophy class="h-10 w-10 text-yellow-500"/>
            </div>

            <h2 class="text-xl font-bold text-primary">
                {{ __('Gefeliciteerd!') }}
            </h2>

            <p class="mt-2 text-sm text-muted">
                {{ __('Je hebt een nieuwe high score behaald op') }} <span
                    class="font-semibold text-primary">{{ $workoutExercise->exercise->name }}</span>!
            </p>

            <div
                class="my-4 inline-flex items-center gap-2 rounded-xl bg-info/10 px-4 py-2 text-info font-black text-lg">
                <x-lucide-trophy class="h-5 w-5 text-yellow-500"/>
                <span>{{ $newHighScoreValue ?? $highestScore }} kg</span>
            </div>

            <div class="mt-4">
                <x-button.default variant="primary" class="w-full"
                                  x-on:click="$dispatch('close-modal', 'congratulations-pr-strength-{{ $workoutExercise->id }}')">
                    {{ __('Geweldig!') }}
                </x-button.default>
            </div>
        </div>
    </x-modal>
</x-card.default>

