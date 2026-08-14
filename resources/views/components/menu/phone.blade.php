@php
    $navItems = [
        ['route' => 'dashboard',        'icon' => 'house', 'label' => 'Home'],
//        ['route' => 'workouts.index',   'icon' => 'layers', 'label' => 'Workouts'],
        ['route' => 'profile.index', 'icon' => 'user', 'label' => 'Profile'],
    ]
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
</nav>
