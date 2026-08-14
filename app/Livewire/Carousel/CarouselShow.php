<?php

namespace App\Livewire\Carousel;

use App\Models\Carousel;
use Livewire\Component;

class CarouselShow extends Component
{
    public function render()
    {
        $items = Carousel::where('is_active', true)->inRandomOrder()->limit(5)->get();

        return view('livewire.carousel.carousel-show', [
            'items' => $items,
        ]);
    }
}
