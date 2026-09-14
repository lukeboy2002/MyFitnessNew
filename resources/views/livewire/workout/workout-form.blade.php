@php
    $title = $workout && $workout->exists
        ? __('Update Workout')
        : __('Create Workout');
@endphp

<div class="py-12 space-y-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        {{-- Workout Details --}}
        <x-card
            :title="$title"
            :description="__('Workout details')"
            class="p-6"
        >
            <x-slot:actions>
                <x-link.default
                    variant="outline"
                    href="{{ route('workout.index') }}"
                >
                    {{ __('Back to workouts') }}
                </x-link.default>
            </x-slot:actions>

            <form wire:submit="save" class="space-y-6">
                <div>
                    <x-form.label for="name" :value="__('Name')"/>
                    <x-form.input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.live="name"
                        required
                        autofocus
                    />
                    <x-form.error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <x-link.default
                        variant="outline"
                        href="{{ route('workout.index') }}"
                        class="w-full"
                    >
                        {{ __('Cancel') }}
                    </x-link.default>

                    <x-button.default
                        type="submit"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                    >
                        {{ __('Save') }}
                    </x-button.default>

                    <div
                        wire:loading
                        wire:target="save"
                        class="text-sm text-muted"
                    >
                        {{ __('Saving') }}
                    </div>
                </div>
            </form>
        </x-card>

        {{-- Exercises --}}
        @if ($workout && $workout->exists)
            <x-card
                :title="__('Exercises') . ' (' . $workout_exercises->count() . ')'"
                :description="__('Manage the exercises and sets for this workout.')"
                class="p-6"
            >
                @if ($workout_exercises->isEmpty())
                    {{-- Empty state --}}
                    <div class="p-8 text-center border-2 border-dashed border-border rounded-lg">
                        <x-lucide-dumbbell class="w-12 h-12 text-secondary mx-auto mb-3 opacity-60"/>

                        <h3 class="text-base font-medium text-primary mb-1">
                            {{ __('No exercises added yet') }}
                        </h3>

                        <p class="text-xs text-muted mb-4">
                            {{ __('Add exercises to this workout to start building your routine.') }}
                        </p>

                        <x-button.default
                            type="button"
                            variant="primary"
                            icon="plus"
                            x-on:click="$dispatch('open-modal', 'add-exercise-modal')"
                        >
                            {{ __('Add exercise') }}
                        </x-button.default>
                    </div>
                @else
                    {{-- Exercise list --}}
                    <div class="space-y-4">
                        @foreach ($workout_exercises as $we)
                            @include('livewire.workout.partials.exercise-card', ['we' => $we])
                        @endforeach
                    </div>

                    {{-- Add Exercise Button --}}
                    <div class="flex justify-end mt-4">
                        <x-button.default
                            type="button"
                            variant="ghost"
                            icon="plus"
                            x-on:click="$dispatch('open-modal', 'add-exercise-modal')"
                        >
                            {{ __('Add exercise') }}
                        </x-button.default>
                    </div>
                @endif
            </x-card>

            {{-- Add Exercise Modal --}}
            @include('livewire.workout.partials.add-exercise-modal')
        @endif
    </div>

    {{-- Delete Modals --}}
    @include('livewire.workout.partials.delete-exercise-modal')
    @include('livewire.workout.partials.delete-workout-modal')
</div>
