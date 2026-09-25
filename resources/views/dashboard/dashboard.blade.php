<x-app-layout pageTitle="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary flex items-center gap-1.5">
            Hoi, {{ auth()->user()->username }}!
            <span>
                <x-lucide-hand class="w-8 h-8 text-secondary"/>
            </span>
        </h1>
        <p class="text-muted text-sm mt-1">
            {{ now()->isoFormat('dddd D MMMM') }}
        </p>

        <div class="flex flex-col gap-6 w-full mt-6">
            <livewire:muscle.muscle-progress/>
            @include('dashboard.partials.active-session')
            <div class="flex flex-col md:flex-row justify-between gap-6 w-full">
                @include('dashboard.partials.last-workout')
                @include('dashboard.partials.this-week')
            </div>

            {{-- PERSONAL RECORDS--}}
            @include('dashboard.partials.personal-records')

            <div class="flex flex-col md:flex-row justify-between gap-6 w-full">
                {{-- MY EXERCISES--}}
                @if ($myExercises->isNotEmpty())
                    @include('dashboard.partials.own-exercises')
                @endif
                {{-- RECENT WORKOUTS --}}
                @include('dashboard.partials.recent-workouts')
            </div>
        </div>
    </div>
</x-app-layout>
