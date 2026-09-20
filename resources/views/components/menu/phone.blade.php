@php
    $navItems = [
        ['route' => 'dashboard',        'icon' => 'house', 'label' => 'Home'],
        ['route' => 'exercises.index',  'icon' => 'activity', 'label' => __('Exercises')],
        ['route' => 'workout.index',   'icon' => 'layers', 'label' => __('Workouts')],
        ['route' => 'profile.index', 'icon' => 'user', 'label' => __('Profile')],
    ]
@endphp

@php
    $activeSession = auth()->user()?->workoutSessions()
        ->where('completed', false)
        ->latest('id')
        ->first();
@endphp

<nav
    class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface backdrop-blur-sm border-t border-border flex items-stretch safe-area-bottom">

    @foreach ($navItems as $item)
        <a href="{{ route($item['route']) }}"
           class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 min-h-14
                      text-xs font-medium transition-colors
                      {{ request()->routeIs(str_replace('.index', '.*', $item['route']))
                          ? 'text-secondary'
                          : 'text-primary hover:text-secondary' }}">

            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-6 h-6"/>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
    <div
        class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 min-h-14
                      text-xs font-medium transition-colors">
    </div>
    @if (! $activeSession)
        <form method="POST" action="{{ route('sessions.start-empty') }}" class="relative">
            @csrf


            <button
                class="absolute -top-5 right-5 bg-secondary min-h-12 min-w-12 rounded-full text-primary flex items-center justify-center">
                <x-lucide-play class="h-6 w-6"/>

            </button>

        </form>
    @endif
</nav>
