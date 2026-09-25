<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\WorkoutSession;
use App\Models\WorkoutSessionExercise;
use App\Models\WorkoutSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkoutSessionController extends Controller
{
    /**
     * Start a new workout session.
     */
    public function start(Request $request): RedirectResponse
    {
        if ($activeSession = $this->activeSession($request)) {
            return redirect()
                ->route('sessions.show', $activeSession)
                ->with('warning', __('You already have an active workout.'));
        }

        $request->validate([
            'workout_id' => [
                'required',
                'integer',
                'exists:workouts,id',
            ],
        ]);

        /* Get the user's active workout. */
        $workout = Workout::query()
            ->where('id', $request->integer('workout_id'))
            ->where('user_id', $request->user()->id)
            ->where('is_archived', false)
            ->with(['workoutExercises.workoutExerciseSets'])
            ->firstOrFail();

        /* Create the workout session. */
        $session = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'workout_id' => $workout->id,
            'started_at' => now(),
            'completed' => false,
        ]);

        /* Create actual workout sets from the workout template sets. */
        foreach ($workout->workoutExercises as $workoutExercise) {
            WorkoutSessionExercise::create([
                'workout_session_id' => $session->id,
                'workout_exercise_id' => $workoutExercise->id,
                'order' => $workoutExercise->order,
                'removed' => false,
            ]);

            foreach ($workoutExercise->workoutExerciseSets as $templateSet) {
                WorkoutSet::create([
                    'workout_session_id' => $session->id,
                    'workout_exercise_set_id' => $templateSet->id,
                    'reps' => null,
                    'weight' => null,
                    'duration_seconds' => $templateSet->target_duration_seconds,
                    'distance_km' => $templateSet->target_distance_km !== null ? (float) $templateSet->target_distance_km : null,
                    'metric' => null,
                    'metric_value' => null,
                    'incline_percent' => $templateSet->target_incline_percent !== null ? (float) $templateSet->target_incline_percent : null,
                    'calories_total' => null,
                    'calories_active' => null,
                    'stroke_rate' => null,
                    'pace_seconds' => null,
                    'floors' => null,
                    'rotations' => null,
                    'avg_speed' => null,
                    'mets' => null,
                    'watts' => null,
                    'avg_heart_rate' => null,
                    'max_heart_rate' => null,
                    'completed' => false,
                    'notes' => null,
                ]);
            }
        }

        return redirect()->route('sessions.show', $session);
    }

    private function activeSession(Request $request): ?WorkoutSession
    {
        return WorkoutSession::query()
            ->where('user_id', $request->user()->id)
            ->where('completed', false)
            ->latest('id')
            ->first();
    }

    public function startEmpty(Request $request): RedirectResponse
    {
        if ($activeSession = $this->activeSession($request)) {
            return redirect()
                ->route('sessions.show', $activeSession)
                ->with('warning', __('You already have an active workout.'));
        }

        $session = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'workout_id' => null,
            'started_at' => now(),
            'completed' => false,
        ]);

        return redirect()->route('sessions.show', $session);
    }

    public function show(WorkoutSession $session): View
    {
        abort_unless(
            $session->user_id === auth()->id(),
            403
        );

        $session->load([
            'workout.workoutExercises.workoutExerciseSets',
        ]);

        return view('sessions.show', [
            'session' => $session,
        ]);
    }

    public function complete(WorkoutSession $session): RedirectResponse
    {
        abort_unless(
            $session->user_id === auth()->id(),
            403
        );

        /* Prevent completing twice. */
        if ($session->completed) {
            return redirect()->route('sessions.show', $session);
        }

        /* Complete session. */
        $session->update([
            'completed' => true,
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', __('Workout completed!'));
    }

    public function summary(WorkoutSession $session): View
    {
        abort_unless($session->user_id === auth()->id(), 403);

        $session->load([
            'workout',
            'workoutSessionExercises',
            'workoutSets.workoutExerciseSet.workoutExercise.exercise',
        ]);

        return view('sessions.summary', compact('session'));
    }
}
