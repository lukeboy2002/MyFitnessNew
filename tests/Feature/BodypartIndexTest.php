<?php

use App\Livewire\BodyParts\BodypartIndex;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('bodypart index component renders correctly', function () {
    Http::fake([
        '*/bodyparts*' => Http::response([
            'data' => [
                ['name' => 'back'],
                ['name' => 'chest'],
            ],
        ], 200),
    ]);

    Livewire::test(BodypartIndex::class)
        ->assertStatus(200)
        ->assertSee('back')
        ->assertSee('chest');
});
