<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorUploadController;
use App\Http\Controllers\PersonalRecordsController;
use App\Http\Controllers\WorkoutSessionController;
use App\Livewire\BodyParts\BodypartForm;
use App\Livewire\BodyParts\BodypartIndex;
use App\Livewire\Carousel\CarouselForm;
use App\Livewire\Carousel\CarouselIndex;
use App\Livewire\Exercises\ExercisesForm;
use App\Livewire\Exercises\ExercisesIndex;
use App\Livewire\Exercises\ExercisesShow;
use App\Livewire\Muscle\MuscleForm;
use App\Livewire\Muscle\MuscleIndex;
use App\Livewire\Musclegroups\MusclegroupForm;
use App\Livewire\Musclegroups\MusclegroupIndex;
use App\Livewire\Workout\WorkoutForm;
use App\Livewire\Workout\WorkoutIndex;
use Illuminate\Support\Facades\Route;

Route::passkeys();

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/profile', fn () => view('profile.index'))->name('profile.index');

    Route::get('/workout', WorkoutIndex::class)->name('workout.index');
    Route::get('/workout/create', WorkoutForm::class)->name('workout.create');
    Route::get('/workout/{workout}/edit', WorkoutForm::class)->name('workout.edit');

    Route::get('exercises', ExercisesIndex::class)->name('exercises.index');
    Route::get('exercises/create', ExercisesForm::class)->name('exercises.create');
    Route::get('exercises/{exercise:slug}/edit', ExercisesForm::class)->name('exercises.edit');
    Route::get('exercises/{exercise:slug}', ExercisesShow::class)->name('exercises.show');

    Route::post('/sessions/start', [WorkoutSessionController::class, 'start'])->name('sessions.start');
    Route::get('/sessions/{session}', [WorkoutSessionController::class, 'show'])->name('sessions.show');
    Route::patch('/sessions/{session}/complete', [WorkoutSessionController::class, 'complete'])->name('sessions.complete');
    Route::get('/sessions/{session}/summary', [WorkoutSessionController::class, 'summary'])->name('sessions.summary');

    Route::get(
        '/personal-records', [PersonalRecordsController::class, 'index'])->name('personal-records.index');
    Route::get('/personal-records/{exercise}', [PersonalRecordsController::class, 'show'])->name('personal-records.show');

    // ── TipTapEditor Images ──────────────────────────────────────────────────────────
    Route::post('editor/uploads/images', [EditorUploadController::class, 'store'])->name('editor.uploads.images');
    Route::delete('editor/uploads/images', [EditorUploadController::class, 'destroy'])->name('editor.uploads.images.destroy');

    Route::middleware('can:access-admin')->group(function () {
        Route::get('/carousel', CarouselIndex::class)->name('carousel.index');
        Route::get('/carousel/create', CarouselForm::class)->name('carousel.create');
        Route::get('/carousel/{carousel}/edit', CarouselForm::class)->name('carousel.edit');

        Route::get('/bodyparts', BodypartIndex::class)->name('bodyparts.index');
        Route::get('/bodyparts/create', BodypartForm::class)->name('bodyparts.create');
        Route::get('/bodyparts/{bodypart}/edit', BodypartForm::class)->name('bodyparts.edit');

        Route::get('/musclegroups', MusclegroupIndex::class)->name('musclegroups.index');
        Route::get('/musclegroups/create', MusclegroupForm::class)->name('musclegroups.create');
        Route::get('/musclegroups/{musclegroup}/edit', MusclegroupForm::class)->name('musclegroups.edit');

        Route::get('/muscles', MuscleIndex::class)->name('muscles.index');
        Route::get('/muscles/create', MuscleForm::class)->name('muscles.create');
        Route::get('/muscles/{muscle}/edit', MuscleForm::class)->name('muscles.edit');
    });
});

require __DIR__.'/auth.php';
