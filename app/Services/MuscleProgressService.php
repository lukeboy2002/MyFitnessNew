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
        $completedSetsSubquery = DB::table('workout_sets')
            ->join('workout_sessions', 'workout_sets.workout_session_id', '=', 'workout_sessions.id')
            ->join('workout_exercise_sets', 'workout_sets.workout_exercise_set_id', '=', 'workout_exercise_sets.id')
            ->join('workout_exercises', 'workout_exercise_sets.workout_exercise_id', '=', 'workout_exercises.id')
            ->join('body_part_exercise', 'workout_exercises.exercise_id', '=', 'body_part_exercise.exercise_id')
            ->where('workout_sessions.user_id', $userId)
            ->where('workout_sessions.completed', true)
            ->where('workout_sets.completed', true)
            ->whereBetween('workout_sessions.started_at', [$startDate, $endDate])
            ->groupBy('body_part_exercise.body_part_id')
            ->select([
                'body_part_exercise.body_part_id',
                DB::raw('COUNT(workout_sets.id) as total_sets'),
            ]);

        return DB::table('body_parts')
            ->leftJoinSub($completedSetsSubquery, 'user_sets', function ($join) {
                $join->on('body_parts.id', '=', 'user_sets.body_part_id');
            })
            ->select([
                'body_parts.id',
                'body_parts.name',
                DB::raw('COALESCE(user_sets.total_sets, 0) as total_sets'),
            ])
            ->orderByDesc('total_sets')
            ->orderBy('body_parts.name')
            ->get();
    }

    /**
     * Haal de verdeling per MuscleGroup binnen een BodyPart op.
     */
    public function getSetsPerMuscleGroup(int $userId, int $bodyPartId, CarbonInterface $startDate, CarbonInterface $endDate): Collection
    {
        $completedSetsSubquery = DB::table('workout_sets')
            ->join('workout_sessions', 'workout_sets.workout_session_id', '=', 'workout_sessions.id')
            ->join('workout_exercise_sets', 'workout_sets.workout_exercise_set_id', '=', 'workout_exercise_sets.id')
            ->join('workout_exercises', 'workout_exercise_sets.workout_exercise_id', '=', 'workout_exercises.id')
            ->join('exercise_muscle_group', 'workout_exercises.exercise_id', '=', 'exercise_muscle_group.exercise_id')
            ->where('workout_sessions.user_id', $userId)
            ->where('workout_sessions.completed', true)
            ->where('workout_sets.completed', true)
            ->whereBetween('workout_sessions.started_at', [$startDate, $endDate])
            ->groupBy('exercise_muscle_group.muscle_group_id')
            ->select([
                'exercise_muscle_group.muscle_group_id',
                DB::raw('COUNT(workout_sets.id) as total_sets'),
            ]);

        return DB::table('muscle_groups')
            ->leftJoinSub($completedSetsSubquery, 'user_sets', function ($join) {
                $join->on('muscle_groups.id', '=', 'user_sets.muscle_group_id');
            })
            ->where('muscle_groups.body_part_id', $bodyPartId)
            ->select([
                'muscle_groups.id',
                'muscle_groups.name',
                DB::raw('COALESCE(user_sets.total_sets, 0) as total_sets'),
            ])
            ->orderByDesc('total_sets')
            ->orderBy('muscle_groups.name')
            ->get();
    }
}
