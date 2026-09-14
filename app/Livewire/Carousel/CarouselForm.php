<?php

namespace App\Livewire\Carousel;

use App\Models\Carousel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarouselForm extends Component
{
    use WithFileUploads;

    public ?Carousel $carousel = null;

    #[Rule('nullable|image|max:10240|mimes:jpg,jpeg,png,webp,heic,heif')]
    public $image_path;

    #[Rule('required|min:3|max:255')]
    public ?string $author = '';

    #[Rule('nullable|url:http,https')]
    public ?string $link = '';

    #[Rule('required|boolean')]
    public bool $is_active = true;

    public function mount(?Carousel $carousel = null): void
    {
        $this->carousel = $carousel;

        if ($carousel?->exists) {
            $this->author = $carousel->author;
            $this->link = $carousel->link;
            $this->is_active = $carousel->is_active;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'author' => $this->author,
            'link' => $this->link,
            'is_active' => $this->is_active,
        ];

        $carousel = $this->carousel ?? new Carousel;

        $carousel->saveItem($data, $this->image_path);

        $this->dispatch('carousel-saved');

        flash()->success(__('The carousel item has been saved'));

        return redirect()->route('carousel.index');
    }

    #[Layout('layouts.app', ['pageTitle' => 'Carousel'])]
    public function render()
    {
        return view('livewire.carousel.carousel-form');
    }
}
