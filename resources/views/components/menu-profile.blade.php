<header class="md:hidden sticky top-0 z-40 flex items-center justify-between px-4 h-14 bg-surface backdrop-blur-sm">
    <div class="flex items-center">
        <a href="/" class="flex items-center shrink-0 md:hidden">
            <img src="{{ asset('storage/assets/logo.png') }}" alt="logo" class="w-12 h-12">
            <span
                class="ml-1 font-bold font-theme text-lg tracking-widest text-secondary overflow-hidden whitespace-nowrap transition-opacity duration-300">
                    MyFitness
                </span>
        </a>
    </div>
    <div class="flex md:hidden items-center gap">
        @php
            $previous = url()->previous();
        @endphp

        @if($previous && $previous !== route('dashboard'))
            <x-link variant="ghost"
                    href="{{ $previous }}"
                    icon="arrow-big-left-dash"
                    size="4"
            />
        @endif
        
        <x-link variant="ghost"
                href="{{ route('profile.index') }}"
                icon="cog"
                size="4"
        />
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               class="flex items-center justify-center gap-2 px-2 py-2 rounded-lg transition duration-150 ease-in-out text-primary hover:text-secondary"
               onclick="event.preventDefault(); this.closest('form').submit();">
                <x-lucide-log-out class="w-4 h-4"/>
            </a>
        </form>
    </div>
</header>
