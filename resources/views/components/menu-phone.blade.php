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
    {{-- Quick start workout knop in het midden --}}
    <div class="flex-1 flex items-center justify-center py-2">
        {{--        <a href="{{ route('sessions.start') }}"--}}
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('quick-start-form').submit();"
           class="w-12 h-12 bg-secondary hover:bg-secondary/80
                      rounded-full flex items-center justify-center
                      text-white text-xl shadow-lg shadow-secondary/30
                      transition-transform active:scale-95 -mt-4">
            ▶
        </a>
    </div>
</nav>

{{-- Hidden form voor quick start (geen workout) --}}
{{--<form id="quick-start-form" method="POST" action="{{ route('sessions.start') }}" class="hidden">--}}
<form id="quick-start-form" method="POST" action="#" class="hidden">
    @csrf
</form>
