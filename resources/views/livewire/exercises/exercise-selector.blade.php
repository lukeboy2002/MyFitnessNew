<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="flex-1">
            <x-form.input type="search"
                          wire:model.live.debounce.300ms="search"
                          placeholder="{{ __('Search exercises...') }}"
            />
        </div>
        <div class="sm:w-64">
            <x-form.select wire:model.live="filterMuscleGroup">
                <option value="">
                    {{ __('All muscle groups') }}
                </option>
                @foreach($muscleGroups as $muscleGroup)
                    <option value="{{ $muscleGroup->id }}">
                        {{ $muscleGroup->name }}
                    </option>
                @endforeach
            </x-form.select>
        </div>
    </div>

    <div class="max-h-[60vh] space-y-2 overflow-y-auto">
        @forelse($exercises as $exercise)
            <button type="button"
                    wire:click="selectExercise({{ $exercise->id }})"
                    wire:key="exercise-{{ $exercise->id }}"
                    class="flex w-full items-center justify-between gap-4 rounded-xl border border-border bg-card/20 p-3 text-left transition hover:bg-card/40"
            >
                <div class="min-w-0">
                    <div class="font-medium text-primary">
                        {{ $exercise->name }}
                    </div>

                    @if($exercise->muscleGroups->isNotEmpty())
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($exercise->muscleGroups as $muscleGroup)
                                <span class="text-xs text-muted">
                                    {{ $muscleGroup->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <x-lucide-plus class="h-5 w-5 shrink-0 text-secondary"/>
            </button>
        @empty
            <div class="py-8 text-center">
                <x-lucide-search class="mx-auto h-8 w-8 text-muted"/>
                <p class="mt-2 text-sm text-muted">
                    {{ __('No exercises found.') }}
                </p>
            </div>
        @endforelse
    </div>
</div>
