<div>
    {{-- Month navigation --}}
    <div class="mb-4 flex items-center justify-between">
        <button
            type="button"
            wire:click="previousMonth"
            class="flex h-10 w-10 items-center justify-center rounded-xl
                   text-muted transition
                   hover:bg-surface-secondary hover:text-primary"
        >
            <x-lucide-chevron-left class="h-5 w-5"/>
        </button>

        <div class="text-center">
            <h2 class="text-lg font-semibold capitalize text-primary">
                {{ $month->translatedFormat('F Y') }}
            </h2>

            <button
                type="button"
                wire:click="currentMonth"
                class="mt-0.5 text-xs text-muted transition hover:text-secondary"
            >
                Vandaag
            </button>
        </div>

        <button
            type="button"
            wire:click="nextMonth"
            class="flex h-10 w-10 items-center justify-center rounded-xl
                   text-muted transition
                   hover:bg-surface-secondary hover:text-primary"
        >
            <x-lucide-chevron-right class="h-5 w-5"/>
        </button>
    </div>

    {{-- Calendar --}}
    <div class="overflow-hidden rounded-2xl border border-border bg-surface">

        {{-- Weekdays --}}
        <div class="grid grid-cols-7 border-b border-border bg-surface-secondary/40">
            @foreach (['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'] as $weekday)
                <div
                    class="px-0.5 py-2.5 text-center text-[10px] font-semibold uppercase
                           tracking-wide text-muted sm:px-1 sm:py-3 sm:text-xs"
                >
                    {{ $weekday }}
                </div>
            @endforeach
        </div>

        {{-- Days --}}
        <div class="grid grid-cols-7">
            @php
                $calendarStart = $month
                    ->copy()
                    ->startOfMonth()
                    ->startOfWeek();

                $calendarEnd = $month
                    ->copy()
                    ->endOfMonth()
                    ->endOfWeek();

                $currentDay = $calendarStart->copy();
            @endphp

            @while ($currentDay->lte($calendarEnd))
                @php
                    $day = $currentDay->copy();

                    $daySessions = $sessions->get(
                        $day->toDateString(),
                        collect()
                    );

                    $visibleSessions = $daySessions->take(-2);
                    $hasMoreSessions = $daySessions->count() > 2;

                    $latestSession = $daySessions->last();

                    $isCurrentMonth = $day->month === $month->month;
                    $isToday = $day->isToday();
                @endphp

                <div class="min-h-20 border-b border-r border-border p-1
                          sm:min-h-32 sm:p-2"
                >
                    {{-- Day number --}}
                    <div class="mb-1 flex justify-end sm:mb-1.5">
                        @if ($isToday)
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full
                                       bg-secondary text-[11px] font-semibold text-primary
                                       sm:h-7 sm:w-7 sm:text-xs"
                            >
                                {{ $day->day }}
                            </span>
                        @else
                            <span
                                @class([
                                    'flex h-6 w-6 items-center justify-center rounded-full text-[11px]',
                                    'text-primary' => $isCurrentMonth,
                                    'text-muted/30' => ! $isCurrentMonth,
                                    'sm:h-7 sm:w-7 sm:text-xs' => true,
                                ])
                            >
                                {{ $day->day }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col items-center justify-between sm:hidden">
                        @if ($latestSession)
                            <a href="{{ $latestSession->completed ? route('sessions.summary', $latestSession) : route('sessions.show', $latestSession) }}"
                               wire:navigate
                               @class([
                                   'flex h-6 min-w-6 max-w-full items-center justify-center rounded-full px-1.5',
                                   'bg-danger/20 text-danger animate-pulse'
                                       => ! $latestSession->completed,
                                   'bg-success/20 text-success'
                                       => $latestSession->completed,
                               ])
                               title="{{ $latestSession->workout_name }}"
                            >
                                <span class="ml-1 max-w-16 truncate text-[10px] font-medium">
                                    {{ $latestSession->workout_name }}
                                </span>
                            </a>
                        @endif
                        @if ($daySessions->count() > 1)
                            <div
                                x-data="{ open: false }"
                                @click.outside="open = false"
                                @keydown.escape.window="open = false"
                                class="relative shrink-0"
                            >
                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="flex h-6 w-6 items-center justify-center rounded-lg
                   text-muted transition
                   hover:bg-surface-secondary hover:text-primary"
                                    :aria-expanded="open"
                                >
                                    <x-lucide-ellipsis class="h-4 w-4"/>
                                </button>

                                {{-- Mobile popover --}}
                                <div
                                    x-cloak
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="translate-y-1 opacity-0"
                                    x-transition:enter-end="translate-y-0 opacity-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="translate-y-0 opacity-100"
                                    x-transition:leave-end="translate-y-1 opacity-0"
                                    class="absolute bottom-full right-0 z-50 mb-2 w-56"
                                >
                                    <div
                                        class="rounded-xl border border-border bg-surface p-3 text-left shadow-xl"
                                    >
                                        <div class="mb-2 text-xs font-semibold text-muted">
                                            Workouts
                                        </div>

                                        <div class="flex max-h-48 flex-col gap-1 overflow-y-auto">
                                            @foreach ($daySessions as $session)
                                                <a
                                                    href="{{ $session->completed
                                ? route('sessions.summary', $session)
                                : route('sessions.show', $session) }}"
                                                    wire:navigate
                                                    @click="open = false"
                                                    class="flex items-center gap-2 rounded-lg px-2 py-1.5
                                   text-xs text-primary transition
                                   hover:bg-surface-secondary"
                                                >
                                                    @if ($session->completed)
                                                        <x-lucide-check
                                                            class="h-3.5 w-3.5 shrink-0 text-muted"
                                                        />
                                                    @else
                                                        <x-lucide-play
                                                            class="h-3.5 w-3.5 shrink-0 text-secondary"
                                                        />
                                                    @endif

                                                    <span class="min-w-0 truncate">
                                {{ $session->workout_name }}
                            </span>

                                                    <span class="ml-auto shrink-0 text-[10px] text-muted">
                                {{ $session->started_at->format('H:i') }}
                            </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Desktop / tablet --}}
                    <div class="hidden flex-col gap-1 sm:flex">
                        @foreach ($visibleSessions as $session)
                            <a href="{{ $session->completed
                                    ? route('sessions.summary', $session)
                                    : route('sessions.show', $session) }}"
                               wire:navigate
                                @class([
                                    'group flex items-center gap-1.5 rounded-lg px-2 py-1.5
                                     text-left text-xs transition',
                                    'bg-danger/20 text-danger'
                                        => ! $session->completed,
                                    'bg-success/20 text-success'
                                        => $session->completed,
                                ])
                            >
                                @if ($session->completed)
                                    <x-lucide-check-circle
                                        class="h-3.5 w-3.5 shrink-0"/>
                                @else
                                    <span class="relative flex h-3.5 w-3.5 shrink-0">
                                        <span
                                            class="absolute inline-flex h-full w-full
                                                   animate-ping rounded-full bg-secondary/40"
                                        ></span>

                                        <x-lucide-play
                                            class="relative h-3.5 w-3.5"
                                        />
                                    </span>
                                @endif
                                <span class="min-w-0 truncate font-medium">
                                    {{ $session->workout_name }}
                                </span>
                            </a>
                        @endforeach

                        {{--                        @if ($hasMoreSessions)--}}
                        {{--                            <div class="flex justify-end">--}}
                        {{--                                <span--}}
                        {{--                                    class="flex h-5 w-5 items-center justify-center text-muted"--}}
                        {{--                                    title="{{ $daySessions->count() - 2 }} extra workouts"--}}
                        {{--                                >--}}
                        {{--                                    <x-lucide-ellipsis class="h-4 w-4"/>--}}
                        {{--                                </span>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}
                        @if ($hasMoreSessions)
                            <div class="relative flex justify-end">
                                <button
                                    type="button"
                                    class="group/more flex h-6 w-6 items-center justify-center rounded-lg
                   text-muted transition
                   hover:bg-surface-secondary hover:text-primary
                   focus:outline-none"
                                >
                                    <x-lucide-ellipsis class="h-4 w-4"/>

                                    {{-- Tooltip / popover --}}
                                    <div
                                        class="pointer-events-none absolute bottom-full right-0 z-50 mb-2
                       w-56 translate-y-1 opacity-0 transition-all duration-150
                       group-hover/more:pointer-events-auto
                       group-hover/more:translate-y-0
                       group-hover/more:opacity-100
                       group-focus-within/more:pointer-events-auto
                       group-focus-within/more:translate-y-0
                       group-focus-within/more:opacity-100"
                                    >
                                        <div
                                            class="rounded-xl border border-border bg-surface p-3 text-left shadow-xl"
                                        >
                                            <div class="mb-2 text-xs font-semibold text-muted">
                                                Workouts
                                            </div>

                                            <div class="flex flex-col gap-1">
                                                @foreach ($daySessions as $session)
                                                    <a
                                                        href="{{ $session->completed
                                    ? route('sessions.summary', $session)
                                    : route('sessions.show', $session) }}"
                                                        wire:navigate
                                                        class="flex items-center gap-2 rounded-lg px-2 py-1.5
                                       text-xs text-primary transition
                                       hover:bg-surface-secondary"
                                                    >
                                                        @if ($session->completed)
                                                            <x-lucide-check
                                                                class="h-3.5 w-3.5 shrink-0 text-muted"
                                                            />
                                                        @else
                                                            <x-lucide-play
                                                                class="h-3.5 w-3.5 shrink-0 text-secondary"
                                                            />
                                                        @endif

                                                        <span class="min-w-0 truncate">
                                    {{ $session->workout_name }}
                                </span>

                                                        <span class="ml-auto shrink-0 text-[10px] text-muted">
                                    {{ $session->started_at->format('H:i') }}
                                </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @endif
                    </div>

                </div>

                @php
                    $currentDay->addDay();
                @endphp
            @endwhile
        </div>
    </div>
</div>
