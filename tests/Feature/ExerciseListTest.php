<?php

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('exercise list component renders correctly', function () {
    Http::fake([
        '*/exercises*' => Http::response([
            'data' => [
                [
                    'id' => '1',
                    'name' => 'Push Up',
                    'imageUrl' => 'http://example.com/image.jpg',
                    'difficulty' => 'beginner',
                    'exerciseTypes' => ['strength'],
                    'bodyParts' => ['chest'],
                    'targetMuscles' => ['pectorals'],
                ],
            ],
        ], 200),
    ]);

    Livewire::test('exercise-list')
        ->assertStatus(200)
        ->assertSee('Push Up')
        ->assertSee('Beginner');
});

test('exercise list loads more exercises on loadMore', function () {
    Http::fake([
        '*/exercises?limit=25&offset=0' => Http::response([
            'data' => array_map(fn ($i) => [
                'id' => (string) $i,
                'name' => "Exercise $i",
                'imageUrl' => 'http://example.com/image.jpg',
                'difficulty' => 'beginner',
                'exerciseTypes' => ['strength'],
                'bodyParts' => ['chest'],
                'targetMuscles' => ['pectorals'],
            ], range(1, 25)),
        ], 200),
        '*/exercises?limit=25&offset=25' => Http::response([
            'data' => [
                [
                    'id' => '26',
                    'name' => 'New Exercise',
                    'imageUrl' => 'http://example.com/image.jpg',
                    'difficulty' => 'beginner',
                    'exerciseTypes' => ['strength'],
                    'bodyParts' => ['chest'],
                    'targetMuscles' => ['pectorals'],
                ],
            ],
        ], 200),
    ]);

    Livewire::test('exercise-list')
        ->assertSee('Exercise 1')
        ->assertDontSee('New Exercise')
        ->call('loadMore')
        ->assertSee('New Exercise');
});
