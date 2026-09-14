<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4">

        <div class="flex items-start gap-4">

            <div class="size-20 shrink-0 overflow-hidden rounded-xl bg-surface-hover">
                @if ($exercise->image_path)
                    <img
                        src="{{ asset('storage/' . $exercise->image_path) }}"
                        alt="{{ $exercise->name }}"
                        class="size-full object-cover"
                    >
                @else
                    <div class="flex size-full items-center justify-center">
                        <x-lucide-biceps-flexed class="size-10 text-muted"/>
                    </div>
                @endif
            </div>

            <div class="min-w-0 flex-1">

                <h1 class="text-xl font-semibold text-primary">
                    {{ $exercise->name }}
                </h1>

                @if ($exercise->muscleGroups->isNotEmpty())
                    <div class="mt-2 flex flex-wrap gap-2">
                        <div class="text-xs font-semibold text-muted flex gap-1">
                            <x-lucide-person-standing class="h-3 w-3"/>
                            @foreach ($exercise->muscleGroups as $muscleGroup)
                                <div class="text-xs text-secondary">
                                    {{ $muscleGroup->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($exercise->muscles->isNotEmpty())
                    <div class="mt-1 space-y-1">
                        @foreach($exercise->muscles->groupBy('pivot.role') as $role => $muscles)
                            <div class="flex gap-1">
                                {{-- Role --}}
                                <div class="text-xs font-semibold text-muted flex gap-1">
                                    <x-lucide-biceps-flexed class="h-3 w-3"/>{{ ucfirst($role) }}:
                                </div>
                                {{-- Muscles --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($muscles as $muscle)
                                        <div class="text-xs text-secondary">
                                            {{ $muscle->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if ($exercise->description)
            <p class="text-sm leading-relaxed text-secondary">
                {{ $exercise->description }}
            </p>
        @endif

    </div>


    {{-- Tabs --}}
    <div class="overflow-x-auto border-b border-border">
        <div class="flex min-w-max gap-6">
            @foreach ([
                'overview' => 'Overview',
                'how-to' => 'How To',
                'history' => 'History',
                'progress' => 'Progress',
                'high-scores' => 'High Scores',
            ] as $key => $label)
                <button
                    type="button"
                    wire:click="setTab('{{ $key }}')"
                    class="border-b-2 px-1 pb-3 text-sm font-medium transition
                        {{ $tab === $key
                            ? 'border-secondary text-primary'
                            : 'border-transparent text-muted hover:text-primary'
                        }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>


    {{-- Content --}}
    <div>
        @if ($tab === 'overview')
            <livewire:exercises.overview
                :exercise="$exercise"
                wire:key="exercise-overview-{{ $exercise->id }}"
            />
        @endif
        @if ($tab === 'how-to')
            @include('livewire.exercises.partials.how-to')
        @endif
        @if ($tab === 'history')
            <livewire:exercises.history
                :exercise="$exercise"
                wire:key="exercise-history-{{ $exercise->id }}"
            />
        @endif
        @if ($tab === 'progress')
            <livewire:exercises.progress-chart
                :exercise="$exercise"
                wire:key="exercise-progress-{{ $exercise->id }}"
            />
        @endif
        @if ($tab === 'high-scores')
            <livewire:exercises.high-scores
                :exercise="$exercise"
                wire:key="exercise-high-scores-{{ $exercise->id }}"
            />
        @endif
    </div>
</div>
