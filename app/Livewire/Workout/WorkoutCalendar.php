<?php

namespace App\Livewire\Workout;

use App\Models\WorkoutSession;
use Carbon\CarbonInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;

class WorkoutCalendar extends Component
{
    public CarbonInterface $month;

    public function mount(): void
    {
        $this->month = now()->startOfMonth();
    }

    public function previousMonth(): void
    {
        $this->month = $this->month
            ->copy()
            ->subMonth()
            ->startOfMonth();
    }

    public function nextMonth(): void
    {
        $this->month = $this->month
            ->copy()
            ->addMonth()
            ->startOfMonth();
    }

    public function currentMonth(): void
    {
        $this->month = now()->startOfMonth();
    }

    #[Layout('layouts.app', ['pageTitle' => 'Workouts'])]
    public function render()
    {
        $sessions = WorkoutSession::query()
            ->where('user_id', auth()->id())
            ->whereBetween('started_at', [
                $this->month->copy()->startOfMonth(),
                $this->month->copy()->endOfMonth(),
            ])
            ->with('workout')
            ->oldest('started_at')
            ->get()
            ->groupBy(
                fn (WorkoutSession $session) => $session->started_at->toDateString()
            );

        return view('livewire.workout.workout-calendar', [
            'sessions' => $sessions,
        ]);
    }
}
