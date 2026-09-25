<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MuscleProgressService
{
    /**
     * Haal het aantal voltooide sets per BodyPart op voor een specifieke periode.
     */
    public function getSetsPerBodyPart(int $userId, CarbonInterface $startDate, CarbonInterface $endDate): Collection
    {
        return DB::table('body_parts')
            ->join('body_part_exercise', 'body_parts.id', '=', 'body_part_exercise.body_part_id')
            ->join('workout_exercises', 'body_part_exercise.exercise_id', '=', 'workout_exercises.exercise_id')
            ->join('workout_exercise_sets', 'workout_exercises.id', '=', 'workout_exercise_sets.workout_exercise_id')
            ->join('workout_sets', 'workout_exercise_sets.id', '=', 'workout_sets.workout_exercise_set_id')
            ->join('workout_sessions', 'workout_sets.workout_session_id', '=', 'workout_sessions.id')
            ->where('workout_sessions.user_id', $userId)
            ->where('workout_sessions.completed', true)
            ->where('workout_sets.completed', true)
            ->whereBetween('workout_sessions.started_at', [$startDate, $endDate])
            ->groupBy('body_parts.id', 'body_parts.name')
            ->select([
                'body_parts.id',
                'body_parts.name',
                DB::raw('COUNT(workout_sets.id) as total_sets'),
            ])
            ->orderByDesc('total_sets')
            ->get();
    }

    /**
     * Haal de verdeling per MuscleGroup binnen een BodyPart op.
     */
    public function getSetsPerMuscleGroup(int $userId, int $bodyPartId, CarbonInterface $startDate, CarbonInterface $endDate): Collection
    {
        return DB::table('muscle_groups')
            ->join('exercise_muscle_group', 'muscle_groups.id', '=', 'exercise_muscle_group.muscle_group_id')
            ->join('workout_exercises', 'exercise_muscle_group.exercise_id', '=', 'workout_exercises.exercise_id')
            ->join('workout_exercise_sets', 'workout_exercises.id', '=', 'workout_exercise_sets.workout_exercise_id')
            ->join('workout_sets', 'workout_exercise_sets.id', '=', 'workout_sets.workout_exercise_set_id')
            ->join('workout_sessions', 'workout_sets.workout_session_id', '=', 'workout_sessions.id')
            ->where('muscle_groups.body_part_id', $bodyPartId)
            ->where('workout_sessions.user_id', $userId)
            ->where('workout_sessions.completed', true)
            ->where('workout_sets.completed', true)
            ->whereBetween('workout_sessions.started_at', [$startDate, $endDate])
            ->groupBy('muscle_groups.id', 'muscle_groups.name')
            ->select([
                'muscle_groups.id',
                'muscle_groups.name',
                DB::raw('COUNT(workout_sets.id) as total_sets'),
            ])
            ->orderByDesc('total_sets')
            ->get();
    }
}
