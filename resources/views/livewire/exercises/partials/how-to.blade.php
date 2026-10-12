@if (!empty($exercise->howto))
    <x-card.default variant="primary">
        <h3 class="font-bold text-secondary underline pb-2">{{ __('How to') }}</h3>
        <ol class="list-none pl-4 space-y-2">
            @foreach ($exercise->howto as $step)
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-info"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M11 10.27 7 3.34"/>
                        <path d="m11 13.73-4 6.93"/>
                        <path d="M12 22v-2"/>
                        <path d="M12 2v2"/>
                        <path d="M14 12h8"/>
                        <path d="m17 20.66-1-1.73"/>
                        <path d="m17 3.34-1 1.73"/>
                        <path d="M2 12h2"/>
                        <path d="m20.66 17-1.73-1"/>
                        <path d="m20.66 7-1.73 1"/>
                        <path d="m3.34 17 1.73-1"/>
                        <path d="m3.34 7 1.73 1"/>
                        <circle cx="12" cy="12" r="2"/>
                        <circle cx="12" cy="12" r="8"/>
                    </svg>
                    <span class="text-sm text-muted">{{ $step }}</span>
                </li>
            @endforeach
        </ol>
    </x-card.default>
@endif

@if (!empty($exercise->tips))
    <div class="mt-6">
        <x-card.default variant="primary">
            <h3 class="font-bold text-secondary underline pb-2">{{ __('Tips') }}</h3>
            <ul class="list-none pl-4 space-y-2">
                @foreach ($exercise->tips as $tip)
                    <li class="flex items-start gap-2">
                        <!-- Lucide Check icoon met een eigen kleur -->
                        <svg class="w-5 h-5 text-success"
                             xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>

                        <span class="text-sm text-muted">{{ $tip }}</span>
                    </li>
                @endforeach
            </ul>
        </x-card.default>
    </div>
@endif
