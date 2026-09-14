<?php

namespace App\Livewire\Carousel;

use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CarouselIndex extends Component
{
    use WithPagination;

    #[Url(as: 'active')]
    public string $activeFilter = 'all';

    public $item;

    protected $listeners = [
        'carousel-deleted' => 'refreshEvents',
    ];

    public function updatedActiveFilter(): void
    {
        $this->resetPage();
    }

    public function deleteItem(Carousel $item)
    {
        $this->item = $item;
        $this->dispatch('open-modal', 'delete-carousel-item');
    }

    public function confirmDelete()
    {

        if ($this->item) {

            $this->item->delete();

            if ($this->item->image_path) {
                Storage::disk('public')->delete($this->item->image_path);
            }

            flash()->success(__('The carousel item has been deleted'));

            $this->dispatch('carousel-deleted');
            $this->dispatch('close-modal', 'delete-carousel-item');
            $this->item = null;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Carousel'])]
    public function render()
    {
        $items = Carousel::query()
            ->when($this->activeFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->activeFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->paginate(10);

        return view('livewire.carousel.carousel-index', [
            'items' => $items,
        ]);
    }
}
