<div class="rounded-xl border border-border bg-surface shadow-sm shadow-surface-secondary/20 p-5 w-full">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-border">
        <div class="flex items-center gap-2.5">
            <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
                <x-lucide-activity class="w-5 h-5" />
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-semibold text-primary text-base">
                        {{ __('Muscle Group Progress') }}
                    </h2>
                    @if ($totalSets > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary/15 text-secondary border border-secondary/20">
                            {{ $totalSets }} {{ __('sets') }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-muted mt-0.5">
                    {{ __('Completed sets per body part & muscle group') }}
                </p>
            </div>
        </div>

        {{-- Period Filters --}}
        <div class="flex items-center gap-1 overflow-x-auto pb-1 sm:pb-0">
            @foreach ($periods as $key => $label)
                <button
                    type="button"
                    wire:click="setPeriod('{{ $key }}')"
                    class="px-2.5 py-1 text-xs font-medium rounded-lg transition-all duration-150 cursor-pointer text-nowrap {{ $period === $key ? 'bg-secondary text-white shadow-xs font-semibold' : 'bg-surface-secondary/60 hover:bg-surface-hover text-muted hover:text-primary border border-border/50' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Content --}}
    <div class="mt-4 space-y-3">
        @if ($bodyParts->isEmpty())
            <div class="py-8 text-center">
                <x-lucide-dumbbell class="w-10 h-10 text-muted/50 mx-auto mb-2" />
                <p class="text-sm font-medium text-primary">
                    {{ __('No completed sets recorded in this period.') }}
                </p>
                <p class="text-xs text-muted mt-1">
                    {{ __('Complete workouts to see your volume and muscle balance distribution here.') }}
                </p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($bodyParts as $bodyPart)
                    @php
                        $percentage = $maxSets > 0 ? round(($bodyPart->total_sets / $maxSets) * 100) : 0;
                        $isExpanded = $expandedBodyPartId === $bodyPart->id;
                    @endphp

                    <div
                        wire:key="body-part-{{ $bodyPart->id }}"
                        class="rounded-lg border border-border/70 bg-surface-secondary/40 hover:bg-surface-secondary/70 transition duration-150 overflow-hidden"
                    >
                        {{-- BodyPart Row Button --}}
                        <button
                            type="button"
                            wire:click="toggleBodyPart({{ $bodyPart->id }})"
                            class="w-full text-left p-3.5 flex flex-col gap-2 cursor-pointer group"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="inline-block w-2 h-2 rounded-full bg-secondary shrink-0"></span>
                                    <span class="font-medium text-sm text-primary group-hover:text-secondary transition truncate">
                                        {{ $bodyPart->name }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-xs font-semibold text-primary">
                                        {{ $bodyPart->total_sets }} {{ __('sets') }}
                                    </span>
                                    <span class="text-muted text-xs group-hover:text-secondary transition">
                                        @if ($isExpanded)
                                            <x-lucide-chevron-up class="w-4 h-4" />
                                        @else
                                            <x-lucide-chevron-down class="w-4 h-4" />
                                        @endif
                                    </span>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-surface-alt/40 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                                <div
                                    class="bg-gradient-to-r from-secondary to-amber-500 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $percentage }}%"
                                ></div>
                            </div>
                        </button>

                        {{-- Expanded Muscle Groups Accordion --}}
                        @if ($isExpanded)
                            <div class="px-3.5 pb-3.5 pt-1 border-t border-border/40 bg-surface/60">
                                <div class="text-xs font-medium text-muted mb-2.5 flex items-center justify-between">
                                    <span>{{ __('Muscle Groups in') }} {{ $bodyPart->name }}</span>
                                    <span>{{ $muscleGroups->count() }} {{ __('groups') }}</span>
                                </div>

                                @if ($muscleGroups->isEmpty())
                                    <p class="text-xs italic text-muted py-1">
                                        {{ __('No specific muscle group data for this body part.') }}
                                    </p>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($muscleGroups as $muscleGroup)
                                            @php
                                                $mgPercentage = $bodyPart->total_sets > 0
                                                    ? round(($muscleGroup->total_sets / $bodyPart->total_sets) * 100)
                                                    : 0;
                                            @endphp
                                            <div
                                                wire:key="muscle-group-{{ $muscleGroup->id }}"
                                                class="flex flex-col gap-1 p-2 rounded-md bg-surface-secondary/50 border border-border/30"
                                            >
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-primary font-medium">{{ $muscleGroup->name }}</span>
                                                    <span class="text-muted font-semibold">{{ $muscleGroup->total_sets }} {{ __('sets') }}</span>
                                                </div>
                                                <div class="w-full bg-surface-alt/30 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                                    <div
                                                        class="bg-pink-500 dark:bg-pink-400 h-1.5 rounded-full transition-all duration-500"
                                                        style="width: {{ $mgPercentage }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
