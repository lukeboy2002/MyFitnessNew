@props(['pageTitle' => ''])

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true',
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    }
}"
      x-on:appearance-updated.window="
        if ($event.detail.theme === 'dark' || ($event.detail.theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
      "
      x-on:language-updated.window="window.location.reload()">
<head>
    @include('layouts.head')
</head>
<body class="bg-body text-primary font-sans antialiased transition-colors duration-200 min-h-screen">
<div class="min-h-dvh bg-body flex flex-col gap-10">
    <div class="w-full relative shrink-0">
        {{--        <livewire:carousel.carousel-show/>--}}

    </div>
    <div class="w-full max-w-7xl flex flex-row justify-center mx-auto">
        <main class="w-full">
            <div class="max-w-7xl mx-auto px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

</div>
@livewireScripts
@stack('scripts')
</body>
</html>
