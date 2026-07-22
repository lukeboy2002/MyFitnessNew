<x-app-layout :pageTitle="__('Dashboard')">

    {{-- Welkom --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary flex items-center gap-1.5">
            Hoi, {{ auth()->user()->username }}!
            <span>
            <x-lucide-hand class="w-8 h-8 text-yellow-500"/>
                </span>
        </h1>
        <p class="text-muted text-sm mt-1">
            {{ now()->isoFormat('dddd D MMMM') }}
        </p>
    </div>


</x-app-layout>
