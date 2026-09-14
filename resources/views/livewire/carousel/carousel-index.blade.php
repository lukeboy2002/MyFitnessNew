<div class="px-4 pt-6">
    <x-card title="{{ __('Carousel Items') }}" description="{{ __('All your carousel items') }}">
        <x-slot:actions>
            <div class="flex items-center justify-end pb-1 mb-2 gap-2">
                <x-dropdown.default align="right" width="48">
                    <x-slot name="trigger">
                        <x-button.default variant="outline"
                                          icon="filter"
                                          size="3"
                        />
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown.button wire:click="$set('activeFilter', 'all')">
                            {{ __('All') }}
                        </x-dropdown.button>

                        <x-dropdown.button wire:click="$set('activeFilter', 'active')">
                            {{ __('Active') }}
                        </x-dropdown.button>

                        <x-dropdown.button wire:click="$set('activeFilter', 'inactive')">
                            {{ __('Not active') }}
                        </x-dropdown.button>
                    </x-slot>
                </x-dropdown.default>
                <x-link.default variant="outline"
                                icon="image-plus"
                                size="3"
                                href="{{ route('carousel.create') }}"
                />
            </div>
        </x-slot:actions>


        <div class="grid {{ $items->isEmpty() ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2' }} gap-3">
            @forelse ($items as $item)
                <div class="border border-border bg-surface-secondary rounded-md shadow-sm flex gap-4">
                    <div class="min-h-12">
                        <img src="{{ $item->image_path }}" alt="{{ $item->author }}"
                             class="w-auto h-12 object-cover rounded-l-md">
                    </div>

                    <div class="flex flex-col py-2 pr-2.5 w-full">
                        <div class="text-secondary flex flex-1 justify-between">
                            <span class="{{ $item->author ? '' : 'text-xs' }}">
                                {{ $item->author ?? 'Author unknown' }}
                            </span>
                            @if($item->is_active)
                                <x-lucide-circle-check class="w-5 h-5 text-success"/>
                            @else
                                <x-lucide-circle-x class="w-5 h-5 text-error"/>
                            @endif
                        </div>

                        <div class="flex item-center justify-end gap-2 mt-auto">
                            <x-link.default variant="outline"
                                            icon="edit-2"
                                            size="3"
                                            href="{{ route('carousel.edit', $item) }}"
                            />
                            <x-button.default variant="outline"
                                              icon="trash-2"
                                              size="3"
                                              wire:click="deleteItem({{ $item->id }})"
                            />
                        </div>
                    </div>
                </div>

            @empty
                <div class="p-8 text-center mb-8">
                    <x-lucide-gallery-horizontal class="w-16 h-16 text-muted mx-auto mb-3"/>
                    <p class="text-muted text-sm mb-3">
                        {{ __("You don't have any carousel items yet") }}
                    </p>
                    <x-link.default variant="primary" href="{{ route('carousel.create') }}">
                        {{ __('Create Carousel Item') }}
                    </x-link.default>
                </div>
            @endforelse

        </div>
        <div class="pt-2">
            {{ $items->links() }}
        </div>
    </x-card>

    <x-modal name="delete-carousel-item" focusable>
        <form wire:submit="confirmDelete" class="p-6">
            <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                <h2 class="text-xl font-semibold text-secondary">
                    {{ __('Delete Carousel Item') }}
                </h2>
                <x-button.default variant="ghost"
                                  type="button"
                                  icon="x"
                                  size="5"
                                  x-on:click="$dispatch('close-modal', 'delete-carousel-item')"
                />
            </div>

            <div class="py-4">
                <div class="flex justify-center mb-4 text-danger">
                    <x-lucide-circle-alert class="h-12 w-12"/>
                </div>
                <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                    {{ __('Are you sure you want to delete this carousel item?') }}
                </h3>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                <x-button.default variant="outline"
                                  type="button"
                                  x-on:click="$dispatch('close-modal', 'delete-carousel-item')"
                                  class="w-full"
                >
                    {{ __('No') }}
                </x-button.default>
                <x-button.default variant="primary"
                                  class="w-full"
                >
                    {{ __('Yes') }}
                </x-button.default>
            </div>
        </form>
    </x-modal>
</div>
