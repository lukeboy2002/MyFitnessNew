@php
    use App\Enum\ExerciseType;
@endphp

<div
    wire:key="workout-exercise-{{ $we->id }}"
    class="border border-border rounded-lg bg-surface/50 p-4 transition hover:border-secondary/50"
>
    {{-- Exercise Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border/60">
        <div class="flex items-center gap-3">
            <span
                class="flex items-center justify-center w-7 h-7 rounded-full bg-secondary/15 text-secondary text-xs font-bold shrink-0">
                {{ $we->order }}
            </span>

            @if ($we->exercise->image_path)
                <img
                    src="{{ asset('storage/' . $we->exercise->image_path) }}"
                    alt="{{ $we->exercise->name }}"
                    class="w-12 h-12 rounded-lg object-cover border border-border bg-surface shrink-0"
                >
            @else
                <div
                    class="w-12 h-12 rounded-lg border border-border bg-surface-hover flex items-center justify-center text-muted shrink-0">
                    <x-lucide-dumbbell class="w-6 h-6"/>
                </div>
            @endif

            <div>
                <h4 class="font-semibold text-primary text-base leading-tight">
                    {{ $we->exercise->name }}
                </h4>

                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-secondary/10 text-secondary capitalize">
                        {{ $we->exercise->type->value }}
                    </span>

                    @foreach ($we->exercise->bodyParts as $bp)
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-surface-hover text-muted">
                            {{ $bp->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-1 self-end sm:self-center">
            <x-button.default
                type="button"
                variant="ghost"
                size="4"
                icon="chevron-up"
                title="{{ __('Move Up') }}"
                wire:click="moveExerciseUp({{ $we->id }})"
                :disabled="$loop->first"
            />

            <x-button.default
                type="button"
                variant="ghost"
                size="4"
                icon="chevron-down"
                title="{{ __('Move Down') }}"
                wire:click="moveExerciseDown({{ $we->id }})"
                :disabled="$loop->last"
            />

            <x-button.danger
                type="button"
                variant="ghost"
                size="4"
                icon="trash-2"
                title="{{ __('Remove Exercise') }}"
                wire:click="deleteExercise({{ $we->id }})"
            />
        </div>
    </div>

    {{-- Notes --}}
    <div class="mt-3">
        <input
            type="text"
            placeholder="{{ __('Add notes / instructions (optional)...') }}"
            value="{{ $we->notes }}"
            wire:change="updateExerciseNotes({{ $we->id }}, $event.target.value)"
            class="w-full text-xs rounded-md border-border bg-surface-hover/50 text-primary placeholder:text-muted focus:border-secondary focus:ring-0 py-1.5 px-3"
        >
    </div>

    {{-- Sets --}}
    <div class="mt-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-muted">
                {{ __('Sets') }} ({{ $we->workoutExerciseSets->count() }})
            </span>
        </div>

        @if ($we->workoutExerciseSets->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-muted border-b border-border/60">
                    <tr>
                        <th class="py-2 px-2 font-medium w-12">{{ __('Set') }}</th>
                        <th class="py-2 px-2 font-medium w-28">{{ __('Type') }}</th>

                        @if ($we->exercise->type === ExerciseType::Cardio)
                            <th class="py-2 px-2 font-medium">{{ __('Duration (min)') }}</th>
                            <th class="py-2 px-2 font-medium">{{ __('Metric') }}</th>
                            <th class="py-2 px-2 font-medium">{{ __('Value') }}</th>
                            <th class="py-2 px-2 font-medium">{{ __('Incline (%)') }}</th>
                        @else
                            <th class="py-2 px-2 font-medium">{{ __('Weight (kg)') }}</th>
                            <th class="py-2 px-2 font-medium">{{ __('Reps') }}</th>
                            <th class="py-2 px-2 font-medium">{{ __('Rest (sec)') }}</th>
                        @endif

                        <th class="py-2 px-2 font-medium w-10"></th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-border/40">
                    @foreach ($we->workoutExerciseSets->sortBy('set_number') as $set)
                        <tr wire:key="workout-exercise-set-{{ $set->id }}">
                            <td class="py-2 px-2 font-semibold text-primary">
                                {{ $set->set_number }}
                            </td>

                            <td class="py-2 px-2">
                                <select
                                    wire:change="updateSet({{ $set->id }}, 'type', $event.target.value)"
                                    class="w-full text-xs rounded border-border bg-surface text-primary"
                                >
                                    @foreach ($set_types as $type)
                                        <option value="{{ $type->value }}" @selected($set->type === $type)>
                                            {{ ucfirst($type->value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            @if ($we->exercise->type === ExerciseType::Cardio)
                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        min="0"
                                        step="1"
                                        value="{{ $set->target_duration_seconds ? $set->target_duration_seconds / 60 : '' }}"
                                        wire:change="updateSet({{ $set->id }}, 'target_duration_minutes', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                    {{--                                    <input--}}
                                    {{--                                        type="number"--}}
                                    {{--                                        min="0"--}}
                                    {{--                                        value="{{ $set->target_duration_seconds }}"--}}
                                    {{--                                        wire:change="updateSet({{ $set->id }}, 'target_duration_seconds', $event.target.value)"--}}
                                    {{--                                        class="w-full text-xs rounded border-border bg-surface text-primary"--}}
                                    {{--                                    >--}}
                                </td>

                                <td class="py-2 px-2">
                                    <select
                                        wire:change="updateSet({{ $set->id }}, 'target_metric', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach ($cardio_metrics as $metric)
                                            <option
                                                value="{{ $metric->value }}" @selected($set->target_metric === $metric)>
                                                {{ ucfirst(str_replace('_', ' ', $metric->value)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        step="0.1"
                                        value="{{ $set->target_metric_value }}"
                                        wire:change="updateSet({{ $set->id }}, 'target_metric_value', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                </td>

                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        step="0.5"
                                        value="{{ $set->target_incline_percent }}"
                                        wire:change="updateSet({{ $set->id }}, 'target_incline_percent', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                </td>
                            @else
                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        step="0.5"
                                        min="0"
                                        value="{{ $set->target_weight }}"
                                        wire:change="updateSet({{ $set->id }}, 'target_weight', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                </td>

                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        min="0"
                                        value="{{ $set->target_reps }}"
                                        wire:change="updateSet({{ $set->id }}, 'target_reps', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                </td>

                                <td class="py-2 px-2">
                                    <input
                                        type="number"
                                        min="0"
                                        value="{{ $set->rest_seconds }}"
                                        wire:change="updateSet({{ $set->id }}, 'rest_seconds', $event.target.value)"
                                        class="w-full text-xs rounded border-border bg-surface text-primary"
                                    >
                                </td>
                            @endif

                            <td class="py-2 px-2 text-right">
                                <button
                                    type="button"
                                    wire:click="removeSet({{ $set->id }})"
                                    class="text-danger hover:opacity-70"
                                    title="{{ __('Remove Set') }}"
                                >
                                    <x-lucide-trash-2 class="size-4"/>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-4 text-center text-xs text-muted">
                {{ __('No sets yet.') }}
            </div>
        @endif

        {{-- Add Set Button --}}
        <div class="flex justify-end items-center mt-3">
            <button
                type="button"
                wire:click="addSet({{ $we->id }})"
                class="inline-flex items-center gap-1 text-xs font-medium text-secondary hover:underline"
            >
                <x-lucide-plus class="w-3.5 h-3.5"/>
                {{ __('Add set') }}
            </button>
        </div>
    </div>
</div>
