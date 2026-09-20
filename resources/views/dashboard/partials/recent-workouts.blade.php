<x-card.default variant="outline"
                class="w-full">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-2">
            <div class="p-2 rounded-lg bg-secondary/10 text-secondary">
                <x-lucide-history class="w-5 h-5"/>
            </div>
            <div>
                <h2 class="font-semibold text-primary">
                    {{ __('Recent workouts') }}
                </h2>
                <p class="text-xs text-muted">
                    {{ __('Your latest training sessions') }}
                </p>
            </div>
        </div>
    </div>

    @if ($recentWorkouts->isNotEmpty())
        <div class="space-y-2">
            @foreach ($recentWorkouts as $session)
                <a href="{{ route('sessions.summary', $session) }}"
                   class="flex items-center justify-between gap-3 p-3 rounded-xl bg-surface-hover/50 border border-border hover:bg-surface-hover transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                            <x-lucide-dumbbell class="w-5 h-5"/>
                        </div>
                        <div class="min-w-0">
                            {{-- Workout Name --}}
                            <p class="font-medium text-sm text-primary truncate">
                                {{ $session->workout->name ?? 'Free Training' }}
                            </p>
                            {{-- Date --}}
                            <p class="text-xs text-muted mt-0.5">
                                {{ $session->completed_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        {{--Duration--}}
                        <div class="text-right ">
                            <p class="text-xs text-muted">
                                {{ __('Duration') }}
                            </p>

                            <p class="text-sm font-semibold text-primary">
                                {{ $session->formatted_duration }}
                            </p>
                        </div>

                        {{--Sets--}}
                        <div class="text-right hidden md:block">
                            <p class="text-xs text-muted">
                                {{ __('Sets') }}
                            </p>
                            <p class="text-sm font-semibold text-primary">
                                {{ $session->completed_sets_count }}
                            </p>
                        </div>
                        <x-lucide-chevron-right class="w-4 h-4 text-muted"/>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="py-8 text-center">
            <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-surface-hover flex items-center justify-center">
                <x-lucide-history class="w-6 h-6 text-muted"/>
            </div>
            <p class="text-sm font-medium text-primary">
                {{ __('No recent workouts') }}
            </p>
            <p class="text-xs text-muted mt-1">
                {{ __('Complete a workout to see your history here.') }}
            </p>
        </div>
    @endif
</x-card.default>
