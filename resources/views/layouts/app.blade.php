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

<x-menu-sidebar/>

<div class="flex flex-col min-h-screen transition-all duration-300"
     :class="sidebarOpen ? 'md:pl-48' : 'md:pl-16 lg:pl-48'">
    <x-menu-main/>

    <main class="pb-24 md:pb-8 flex-1">
        <div class="py-5 max-w-7xl mx-auto w-full">
            <div class="px-4 space-y-10">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
<x-menu-phone/>
@livewireScripts
@stack('scripts')
</body>
</html>
