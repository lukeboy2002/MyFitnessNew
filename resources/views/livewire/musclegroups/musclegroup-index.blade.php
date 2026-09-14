<div>
    <div class="lg:hidden">
        <x-card>
            <div class="p-8 text-center mb-8">
                <x-lucide-tablet-smartphone class="w-16 h-16 text-danger mx-auto mb-3"/>
                <p class="text-muted text-sm mb-3">
                    {{ __("Not available on a mobile device.") }}
                </p>
            </div>
        </x-card>
    </div>

    <div class="hidden lg:block">
        <div class="px-4 pt-6">
            <x-card title="{{ __('Musclegroups') }}" description="{{ __('All available musclegroups') }}">
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
                                <x-dropdown.button wire:click="$set('bodyPartFilter', 'all')">
                                    {{ __('All') }}
                                </x-dropdown.button>

                                @foreach ($bodyparts as $bp)
                                    <x-dropdown.button wire:key="filter-bodypart-{{ $bp->id }}"
                                                       wire:click="$set('bodyPartFilter', '{{ $bp->id }}')">
                                        {{ $bp->name }}
                                    </x-dropdown.button>
                                @endforeach
                            </x-slot>
                        </x-dropdown.default>

                        <x-link.default variant="outline"
                                        icon="square-plus"
                                        size="3"
                                        href="{{ route('musclegroups.create') }}"
                        />
                    </div>
                </x-slot:actions>

                <div
                    class="grid {{ $musclegroups->isEmpty() ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' }} gap-3">

                    @forelse ($musclegroups as $musclegroup)
                        <div wire:key="musclegroup-{{ $musclegroup->id }}"
                             class="border border-border bg-surface-secondary rounded-md shadow-sm flex gap-4">
                            <div class="min-h-24 w-1/3 flex items-center justify-center">
                                <img src="{{ $musclegroup->image_path }}" alt="{{ $musclegroup->name }}"
                                     class="w-auto h-24 object-cover rounded-l-md">
                            </div>

                            <div class="flex flex-col py-2 pr-2.5 w-full">
                                <div class="text-secondary flex flex-1 flex-col justify-between">
                                    <div>
                                        <div class="text-xl text-secondary">
                                            {{ $musclegroup->name }}
                                        </div>
                                        @if ($musclegroup->bodyPart)
                                            <div class="mt-1">
                                                <x-badge.default variant="bodypart">
                                                    {{ $musclegroup->bodyPart->name }}
                                                </x-badge.default>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex item-center justify-end gap-2 mt-auto">
                                        <x-link.default variant="outline"
                                                        icon="edit-2"
                                                        size="3"
                                                        href="{{ route('musclegroups.edit', $musclegroup) }}"
                                        />
                                        <x-button.default variant="outline"
                                                          icon="trash-2"
                                                          size="3"
                                                          wire:click="deleteItem({{ $musclegroup->id }})"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="p-8 text-center mb-8">
                            <x-lucide-person-standing class="w-16 h-16 text-muted mx-auto mb-3"/>
                            <p class="text-muted text-sm mb-3">
                                {{ __("You don't have any musclegroups yet") }}
                            </p>
                            <x-link.default variant="primary" href="{{ route('musclegroups.create') }}">
                                {{ __('Create Musclegroup') }}
                            </x-link.default>
                        </div>
                    @endforelse

                </div>

            </x-card>
        </div>

        <x-modal name="delete-musclegroup" focusable>
            <form wire:submit="confirmDelete" class="p-6">
                <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                    <h2 class="text-xl font-semibold text-secondary">
                        {{ __('Delete musclegroup') }}
                    </h2>
                    <x-button.default variant="ghost"
                                      type="button"
                                      icon="x"
                                      size="5"
                                      x-on:click="$dispatch('close-modal', 'delete-musclegroup')"
                    />
                </div>

                <div class="py-4">
                    <div class="flex justify-center mb-4 text-danger">
                        <x-lucide-circle-alert class="h-12 w-12"/>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                        {{ __('Are you sure you want to delete this musclegroup?') }}
                    </h3>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                    <x-button.default variant="outline"
                                      type="button"
                                      x-on:click="$dispatch('close-modal', 'delete-musclegroup')"
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
</div>

