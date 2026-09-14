<div class="flex flex-col gap-5">
    {{-- Metric buttons --}}
    @if (count($metrics))
        <div class="flex gap-2 overflow-x-auto">
            @foreach ($metrics as $key => $label)
                <button type="button"
                        wire:click="setMetric('{{ $key }}')"
                        class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium transition
                        {{ $metric === $key
                            ? 'bg-secondary text-primary-foreground'
                            : 'bg-surface-hover text-secondary hover:text-primary'
                        }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    @endif

    {{-- Scores --}}
    <div class="overflow-hidden rounded-xl border border-border bg-surface">
        @forelse ($highScores as $index => $score)
            <div class="flex items-center gap-4 border-b border-border p-4 last:border-0">

                {{-- Position --}}
                <div class="flex size-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold bg-surface-hover
                    {{ $index === 0
                        ? 'text-secondary'
                        : 'text-primary'
                    }}">
                    {{ $index + 1 }}
                </div>

                {{-- Score --}}
                <div class="min-w-0 flex-1">
                    <div class="font-semibold text-primary">
                        {{ $formatter->formatMetric(
                            $score['metric'],
                            $score['value']
                        ) }}
                    </div>

                    <div class="mt-1 flex flex-wrap gap-x-2 text-xs text-muted">
                        @if ($score['date'])
                            <span>
                                {{ $score['date'] }}
                            </span>
                        @endif

                        @if ($score['workout'])
                            <span class="text-secondary">
                                •
                            </span>

                            <span>
                                {{ $score['workout'] }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Trophy --}}
                @if ($index === 0)
                    <div class="flex size-9 items-center justify-center">
                        <x-lucide-trophy class="size-5 text-warning"/>
                    </div>
                @endif
            </div>
        @empty
            <div class="flex min-h-64 flex-col items-center justify-center p-8 text-center">
                <x-lucide-trophy class="size-8 text-muted"/>
                <p class="mt-3 text-sm text-muted">
                    {{ __('No records yet.') }}
                </p>
            </div>
        @endforelse
    </div>
</div>
