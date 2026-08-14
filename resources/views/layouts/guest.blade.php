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
<div class="min-h-dvh bg-body flex sm:justify-between flex-col items-center sm:flex-row gap-10 sm:gap-0">
    <div class="w-full sm:w-1/2 relative shrink-0">
        <div class="sm:hidden">
            <livewire:carousel.carousel-show/>
        </div>
        <div class="hidden sm:block">
            <div class="flex flex-col justify-center items-center">
                <img src="{{ asset('storage/assets/logo.png') }}"
                     class="w-110"
                     alt="logo">
                <div class="font-theme sm:text-4xl md:text-5xl lg:text-6xl text-secondary">
                    {{ config('app.name') }}
                </div>
            </div>
        </div>
    </div>
    <div class="w-full max-w-2xl flex flex-row justify-center mx-auto sm:px-4">
        <main class="w-full px-4 sm:px-0">
            {{ $slot }}
        </main>
    </div>

</div>
@livewireScripts
@stack('scripts')
</body>
</html>
