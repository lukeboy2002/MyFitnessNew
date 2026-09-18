<x-card.default variant="outline"
                class="w-full">
    {{-- Header --}}
    <a href="{{ route('exercises.index') }}">
        <div class="flex items-center justify-between relative">
            <div class="flex items-center gap-2">
                <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
                    <x-lucide-biceps-flexed class="w-5 h-5"/>
                </div>
                <div>
                    <h2 class="font-semibold text-primary">
                        {{ __('My exercises') }}
                    </h2>
                    <p class="text-xs text-muted">
                        {{ __('Your personal exercises') }}
                    </p>
                </div>

            </div>
            <div class="text-3xl mr-10 font-bold text-secondary">
                {{ $myExercisesCount }}
            </div>
            <div class="absolute -right-3 -top-3">
                <x-lucide-chevron-up class="h-5 w-5 text-muted rotate-45"/>
            </div>
        </div>

        <div class="mt-5">
            <div class="space-y-2">
                @foreach ($myExercises as $exercise)
                    <a href="{{ route('exercises.show', $exercise->slug) }}"
                       class="flex items-center justify-between gap-3 p-3 rounded-xl border border-border bg-surface-hover/50 hover:bg-surface-hover transition">
                        <div class="flex items-center gap-3 min-w-0">
                            @if ($exercise->image_path)
                                <img src="{{ asset('storage/' . $exercise->image_path) }}"
                                     alt="{{ $exercise->name }}"
                                     class="w-10 h-10 rounded-lg object-cover shrink-0">
                            @else
                                <div
                                    class="w-10 h-10 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                                    @if ($exercise->isCardio())
                                        <x-lucide-heart-pulse class="w-5 h-5"/>
                                    @else
                                        <x-lucide-biceps-flexed class="w-5 h-5"/>
                                    @endif
                                </div>
                            @endif

                            <div class="min-w-0">
                                <p class="font-medium text-sm text-primary truncate">
                                    {{ $exercise->name }}
                                </p>

                                @if ($exercise->muscleGroups->isNotEmpty())
                                    <p class="text-xs text-muted mt-0.5 truncate">
                                        {{ $exercise->muscleGroups->pluck('name')->join(', ') }}
                                    </p>
                                @elseif ($exercise->type)
                                    <p class="text-xs text-muted mt-0.5 capitalize">
                                        {{ $exercise->type->value }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <x-lucide-chevron-right class="w-4 h-4 text-muted"/>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </a>
</x-card.default>
