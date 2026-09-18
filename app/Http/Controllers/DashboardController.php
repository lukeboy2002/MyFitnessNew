<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutSession;
use App\Models\WorkoutSet;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Active Workout Session
        |--------------------------------------------------------------------------
        */

        $activeSession = WorkoutSession::query()
            ->where('user_id', $userId)
            ->where('completed', false)
            ->with([
                'workout',
            ])
            ->latest('started_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Last Workout
        |--------------------------------------------------------------------------
        */

        $lastWorkout = WorkoutSession::query()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->with([
                'workout.workoutExercises',
            ])
            ->withCount([
                'workoutSets as completed_sets_count' => function ($query) {
                    $query->where('completed', true);
                },
            ])
            ->latest('completed_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | This Week
        |--------------------------------------------------------------------------
        */

        $thisWeekSessions = WorkoutSession::query()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->whereBetween('started_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Workouts this week
        |--------------------------------------------------------------------------
        */

        $workoutsThisWeek = $thisWeekSessions->count();

        /*
        |--------------------------------------------------------------------------
        | Recent Workouts
        |--------------------------------------------------------------------------
        */

        $recentWorkouts = WorkoutSession::query()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->with([
                'workout',
            ])
            ->withCount([
                'workoutSets as completed_sets_count' => function ($query) {
                    $query->where('completed', true);
                },
            ])
            ->latest('completed_at')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Personal Records
        |--------------------------------------------------------------------------
        */

        $completedWorkoutSets = WorkoutSet::query()
            ->where('completed', true)
            ->whereHas(
                'workoutSession',
                fn ($query) => $query
                    ->where('user_id', $userId)
                    ->where('completed', true)
            )
            ->with([
                'workoutSession',
                'workoutExerciseSet.workoutExercise.exercise',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Strength Records
        |--------------------------------------------------------------------------
        */

        $highestWeight = (clone $completedWorkoutSets)
            ->where('weight', '>', 0)
            ->orderByDesc('weight')
            ->first();

        $mostReps = (clone $completedWorkoutSets)
            ->where('reps', '>', 0)
            ->orderByDesc('reps')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Cardio Records
        |--------------------------------------------------------------------------
        */

        $longestDuration = (clone $completedWorkoutSets)
            ->where('duration_seconds', '>', 0)
            ->orderByDesc('duration_seconds')
            ->first();

        $longestDistance = (clone $completedWorkoutSets)
            ->where('distance_km', '>', 0)
            ->orderByDesc('distance_km')
            ->first();

        $mostCalories = (clone $completedWorkoutSets)
            ->where('calories_total', '>', 0)
            ->orderByDesc('calories_total')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Completed sets this week
        |--------------------------------------------------------------------------
        */

        $totalSetsThisWeek = WorkoutSession::query()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->whereBetween('started_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->withCount([
                'workoutSets as completed_sets_count' => function ($query) {
                    $query->where('completed', true);
                },
            ])
            ->get()
            ->sum('completed_sets_count');

        /*
        |--------------------------------------------------------------------------
        | Total training time this week
        |--------------------------------------------------------------------------
        */

        $totalSecondsThisWeek = $thisWeekSessions
            ->sum(function (WorkoutSession $session) {

                if (
                    ! $session->started_at ||
                    ! $session->completed_at
                ) {
                    return 0;
                }

                return $session
                    ->started_at
                    ->diffInSeconds(
                        $session->completed_at
                    );
            });

        $hours = floor(
            $totalSecondsThisWeek / 3600
        );

        $minutes = floor(
            ($totalSecondsThisWeek % 3600) / 60
        );

        $totalTrainingTimeThisWeek = $hours > 0
            ? $hours.'h '.$minutes.'m'
            : $minutes.' min';
        /*
        |--------------------------------------------------------------------------
        | My Exercises
        |--------------------------------------------------------------------------
        */

        $myExercises = Exercise::query()
            ->where('user_id', $userId)
            ->with(['muscleGroups'])
            ->latest()
            ->take(5)
            ->get();

        $myExercisesCount = Exercise::query()
            ->where('user_id', $userId)
            ->count();

        return view('dashboard.dashboard', [
            'activeSession' => $activeSession,
            'lastWorkout' => $lastWorkout,
            'workoutsThisWeek' => $workoutsThisWeek,
            'recentWorkouts' => $recentWorkouts,
            'totalSetsThisWeek' => $totalSetsThisWeek,
            'totalSecondsThisWeek' => $totalSecondsThisWeek,
            'totalTrainingTimeThisWeek' => $totalTrainingTimeThisWeek,
            'myExercisesCount' => $myExercisesCount,
            'myExercises' => $myExercises,
            /*
             * Strength
             */
            'highestWeight' => $highestWeight,
            'mostReps' => $mostReps,
            /*
             * Cardio
             */
            'longestDuration' => $longestDuration,
            'longestDistance' => $longestDistance,
            'mostCalories' => $mostCalories,
        ]);
    }
}
