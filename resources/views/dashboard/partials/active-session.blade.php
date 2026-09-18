@if ($activeSession)
    <div class="mt-6">
        <a href="{{ route('sessions.show', $activeSession) }}">
            <x-card.default variant="warning" class="w-full">
                <div x-data="{ startedAt: new Date('{{ $activeSession->started_at->toIso8601String() }}'),
                                    elapsed: '',

                                    updateTimer() {
                                        const now = new Date();

                                        const seconds = Math.floor(
                                            (now - this.startedAt) / 1000
                                        );

                                        const hours = Math.floor(seconds / 3600);

                                        const minutes = Math.floor(
                                            (seconds % 3600) / 60
                                        );

                                        this.elapsed = hours > 0
                                            ? `${hours}h ${minutes}m`
                                            : `${minutes} min`;
                                    }
                                }"
                     x-init="updateTimer();
                                        setInterval(() => updateTimer(), 1000);"
                     class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center shrink-0">
                            <x-lucide-play class="w-6 h-6 text-primary"/>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                                <span
                                                    class="flex w-2.5 h-2.5 rounded-full bg-red-700 animate-pulse"></span>
                                <span class="text-xs text-primary font-medium uppercase tracking-wide">
                                                {{ __('Workout in progress') }}
                                            </span>
                            </div>

                            <h2 class="text-lg font-semibold text-primary mt-1">
                                {{ $activeSession->workout?->name ?? __('Workout') }}
                            </h2>

                            <div class="flex items-center gap-3 mt-1 text-sm text-primary">
                                            <span>
                                                {{ __('Started') }}
                                                {{ $activeSession->started_at?->format('H:i') }}
                                            </span>
                                <span>•</span>
                                <span class="font-medium text-primary"
                                      x-text="elapsed"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card.default>
        </a>

    </div>

@endif
