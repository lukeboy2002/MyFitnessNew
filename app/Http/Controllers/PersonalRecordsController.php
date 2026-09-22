<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutSet;
use Illuminate\Contracts\View\View;

class PersonalRecordsController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $workoutSets = WorkoutSet::query()
            ->where('completed', true)
            ->whereHas(
                'workoutSession',
                fn ($query) => $query
                    ->where('user_id', $userId)
                    ->where('completed', true)
            )
            ->with(['workoutSession', 'workoutExerciseSet.workoutExercise.exercise'])
            ->get();

        $personalRecords = $workoutSets
            ->filter(
                fn (WorkoutSet $set) => $set
                    ->workoutExerciseSet
                    ?->workoutExercise
                    ?->exercise
            )
            ->groupBy(
                fn (WorkoutSet $set) => $set
                    ->workoutExerciseSet
                    ->workoutExercise
                    ->exercise
                    ->id
            )
            ->map(function ($sets) {
                $exercise = $sets
                    ->first()
                    ->workoutExerciseSet
                    ->workoutExercise
                    ->exercise;

                return [
                    'exercise' => $exercise,
                    'highest_weight' => $sets
                        ->whereNotNull('weight')
                        ->sortByDesc('weight')
                        ->first(),
                    'most_reps' => $sets
                        ->whereNotNull('reps')
                        ->sortByDesc('reps')
                        ->first(),
                    'longest_duration' => $sets
                        ->whereNotNull('duration_seconds')
                        ->sortByDesc('duration_seconds')
                        ->first(),
                    'longest_distance' => $sets
                        ->whereNotNull('distance_km')
                        ->sortByDesc('distance_km')
                        ->first(),
                    'most_calories' => $sets
                        ->whereNotNull('calories_total')
                        ->sortByDesc('calories_total')
                        ->first(),
                ];
            })
            ->sortBy(
                fn (array $record) => $record['exercise']->name
            )
            ->values();

        return view('personal-records.index', compact('personalRecords'));
    }

    public function show(Exercise $exercise): View
    {
        return view('personal-records.show', compact('exercise'));
    }
}
