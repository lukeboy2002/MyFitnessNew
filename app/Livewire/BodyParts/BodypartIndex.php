<?php

namespace App\Livewire\BodyParts;

use App\Models\BodyPart;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class BodypartIndex extends Component
{
    public $bodypart;

    protected $listeners = [
        'bodypart-deleted' => 'refreshEvents',
    ];

    public function deleteItem(BodyPart $bodypart)
    {
        $this->bodypart = $bodypart;
        $this->dispatch('open-modal', 'delete-bodypart');
    }

    public function confirmDelete()
    {

        if ($this->bodypart) {

            $this->bodypart->delete();

            if ($this->bodypart->image_path) {
                Storage::disk('public')->delete($this->bodypart->image_path);
            }

            flash()->success(__('The bodypart has been deleted'));

            $this->dispatch('bodypart-deleted');
            $this->dispatch('close-modal', 'delete-bodypart');
            $this->bodypart = null;
        }
    }

    #[Layout('layouts.app', ['pageTitle' => 'Carousel'])]
    public function render()
    {
        $bodyparts = BodyPart::orderBy('name')->get();

        return view('livewire.body-parts.bodypart-index', [
            'bodyparts' => $bodyparts,
        ]);
    }
}
