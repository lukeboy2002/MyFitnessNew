@php
    $adminItems = [
//        ['route' => 'carousel.index',        'icon' => 'gallery-horizontal-end', 'label' => __('Carousel')],
//        ['route' => 'bodyparts.index',  'icon' => 'person-standing', 'label' => __('Body Parts')],
//        ['route' => 'muscles.index',     'icon' => 'biceps-flexed', 'label' => __('Muscles')],

    ];

    $navItems = [
        ['route' => 'dashboard',        'icon' => 'house', 'label' => 'Home'],
//        ['route' => 'exercises.index',  'icon' => 'activity', 'label' => __('Exercises')],
//        ['route' => 'workouts.index',   'icon' => 'layers', 'label' => 'Workouts'],
//        ['route' => 'statistics.index', 'icon' => 'chart-bar', 'label' => 'Stats'],
//        ['route' => 'calendar.index',   'icon' => 'calendar-days', 'label' => 'Calendar'],
//        ['route' => 'watchinfo.index',   'icon' => 'watch', 'label' => 'Watch Info'],
    ]
@endphp

<nav
    class="hidden md:flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-surface border-r border-border transition-all duration-300"
    :class="sidebarOpen ? 'w-48' : 'w-16 lg:w-48'">
    <div class="flex flex-col h-full py-4 overflow-hidden">
        <!-- Logo / Home icon -->
        <div class="flex items-center justify-between px-2 mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center shrink-0">
                <img src="{{ asset('storage/assets/logo.png') }}" alt="logo" class="w-12 h-12">
                <span
                    class="ml-1 font-bold font-theme text-lg tracking-widest text-secondary overflow-hidden whitespace-nowrap transition-opacity duration-300"
                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                    MyFitness
                </span>
            </a>
        </div>

        <div class="flex-1 space-y-2">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-4 py-3 transition-colors
                              {{ request()->routeIs(str_replace('.index', '.*', $item['route']))
                                  ? 'text-secondary bg-surface-hover'
                                  : 'text-primary hover:text-secondary hover:bg-surface-hover' }}">
                    <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-6 h-6 shrink-0"/>
                    <span
                        class="ml-4 font-medium overflow-hidden whitespace-nowrap transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                            {{ $item['label'] }}
                        </span>
                </a>
            @endforeach

            {{-- Quick start session --}}
            {{--            <a href="{{ route('sessions.start') }}"--}}
            <a href="#"
               onclick="event.preventDefault(); document.getElementById('sidebar-quick-start-form').submit();"
               class="flex items-center px-4 py-3 text-primary hover:text-secondary hover:bg-surface-hover transition-colors lg:hidden">
                <div
                    class="w-6 h-6 flex items-center justify-center bg-secondary rounded-full text-white text-[10px] shrink-0">
                    ▶
                </div>
                <span
                    class="ml-4 font-medium overflow-hidden whitespace-nowrap transition-opacity duration-300"
                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                    {{ __('Start Workout') }}
                </span>
            </a>
        </div>
        @if (Auth::user()->is_admin)
            <div class="border-t border-border pt-4 space-y-2">
                @foreach ($adminItems as $item)

                    <a href="{{ route($item['route']) }}"
                       class="flex items-center px-4 py-3 transition-colors
                              {{ request()->routeIs(str_replace('.index', '.*', $item['route']))
                                  ? 'text-secondary bg-surface-hover'
                                  : 'text-primary hover:text-secondary hover:bg-surface-hover' }}">
                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-6 h-6 shrink-0"/>
                        <span
                            class="ml-4 font-medium overflow-hidden whitespace-nowrap transition-opacity duration-300"
                            :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                            {{ $item['label'] }}
                        </span>
                    </a>

                @endforeach
            </div>
        @endif
        <div class="border-t border-border pt-4 space-y-2">
            <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-3 transition-colors
                              {{ request()->routeIs('profile.edit')
                              ? 'text-secondary bg-surface-hover'
                              : 'text-primary hover:text-secondary hover:bg-surface-hover' }}">
                @if (Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                         class="w-8 h-8 rounded-full object-cover shrink-0">
                @else
                    <x-lucide-user-circle class="w-6 h-6 shrink-0"/>
                @endif
                <span
                    class="ml-4 font-medium overflow-hidden whitespace-nowrap transition-opacity duration-300"
                    :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                        {{ __("Profile") }}
                    </span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"

                   class="flex items-center px-4 py-3 transition-colors
                              ? 'text-secondary bg-surface-hover'
                              : 'text-primary hover:text-secondary hover:bg-surface-hover' }}">
                    <x-lucide-log-out class="w-6 h-6 shrink-0"/>
                    <span
                        class="ml-4 font-medium overflow-hidden whitespace-nowrap transition-opacity duration-300"
                        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 lg:opacity-100'">
                        {{ __('Log Out') }}
                    </span>
                </a>

            </form>
        </div>
    </div>
</nav>

{{-- Hidden form voor quick start (geen workout) --}}
{{--<form id="sidebar-quick-start-form" method="POST" action="{{ route('sessions.start') }}" class="hidden">--}}
<form id="sidebar-quick-start-form" method="POST" action="#" class="hidden">
    @csrf
</form>
