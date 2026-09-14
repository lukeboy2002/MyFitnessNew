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
            <x-card title="{{ __('Muscle') }}" description="{{ __('All available muscle') }}">
                <x-slot:actions>

                    <div class="flex items-center justify-end pb-1 mb-2 gap-2">
                        <x-form.search icon="search" type="search" wire:model.live.debounce.300ms="search"
                                       placeholder="{{ __('Search') }}..."
                                       class="w-full"/>

                        <x-dropdown.default align="right" width="48">
                            <x-slot name="trigger">
                                <x-button.default variant="outline"
                                                  icon="filter"
                                                  size="3">
                                    Bodyparts
                                </x-button.default>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown.button wire:click="$set('bodyPartFilter', 'all')">
                                    {{ __('All') }}
                                </x-dropdown.button>

                                @foreach ($bodyparts as $bodypart)
                                    <x-dropdown.button wire:key="filter-bodypart-{{ $bodypart->id }}"
                                                       wire:click="$set('bodyPartFilter', '{{ $bodypart->id }}')">
                                        {{ $bodypart->name }}
                                    </x-dropdown.button>
                                @endforeach
                            </x-slot>
                        </x-dropdown.default>

                        <x-dropdown.default align="right" width="48">
                            <x-slot name="trigger">
                                <x-button.default variant="outline"
                                                  icon="filter"
                                                  size="3">
                                    Musclegroups
                                </x-button.default>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown.button wire:click="$set('muscleGroupFilter', 'all')">
                                    {{ __('All') }}
                                </x-dropdown.button>

                                @foreach ($musclegroups as $musclegroup)
                                    <x-dropdown.button wire:key="filter-musclegroup-{{ $musclegroup->id }}"
                                                       wire:click="$set('muscleGroupFilter', '{{ $musclegroup->id }}')">
                                        {{ $musclegroup->name }}
                                    </x-dropdown.button>
                                @endforeach
                            </x-slot>
                        </x-dropdown.default>

                        <x-link.default variant="outline"
                                        icon="square-plus"
                                        size="4"
                                        href="{{ route('muscles.create') }}"
                        />
                    </div>
                </x-slot:actions>

                <div>
                    @if ($bodyPartFilter !== 'all' || $muscleGroupFilter !== 'all')
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs text-muted">{{ __('Active filters:') }}</span>

                            @if ($bodyPartFilter !== 'all')
                                <button wire:click="$set('bodyPartFilter', 'all')"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded bg-surface-secondary border border-border text-secondary hover:text-danger">
                                    <span>{{ $this->bodyPartFilterName }}</span>
                                    <x-lucide-x class="w-3 h-3"/>
                                </button>
                            @endif

                            @if ($muscleGroupFilter !== 'all')
                                <button wire:click="$set('muscleGroupFilter', 'all')"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded bg-surface-secondary border border-border text-secondary hover:text-danger">
                                    <span>{{ $this->muscleGroupFilterName }}</span>
                                    <x-lucide-x class="w-3 h-3"/>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                <div
                    class="grid {{ $muscles->isEmpty() ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' }} gap-3">

                    @forelse ($muscles as $muscle)
                        <div wire:key="muscle-{{ $muscle->id }}"
                             class="border border-border bg-surface-secondary rounded-md shadow-sm flex gap-4">
                            <div class="min-h-24 w-1/4 flex p-2">
                                <x-lucide-biceps-flexed class="h-12 w-12 text-secondary"/>

                            </div>
                            <div class="flex flex-col py-2 pr-2.5 w-full">
                                <div class="text-secondary flex flex-1 flex-col justify-between">
                                    <div>
                                        <div class="text-xl text-secondary">
                                            {{ $muscle->name }}
                                        </div>
                                        <div class="flex items-center">
                                            @if ($muscle->muscleGroup->bodyPart)
                                                <x-badge.default variant="ghost" icon="person-standing">
                                                    {{ $muscle->muscleGroup->bodyPart->name }}
                                                </x-badge.default>
                                            @endif
                                            <x-lucide-minus class="w-3 h-3 rotate-90"/>
                                            @if ($muscle->muscleGroup)
                                                <x-badge.default variant="ghost" icon="biceps-flexed">
                                                    {{ $muscle->muscleGroup->name }}
                                                </x-badge.default>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex item-center justify-end gap-2 mt-auto">
                                        <x-link.default variant="outline"
                                                        icon="edit-2"
                                                        size="3"
                                                        href="{{ route('muscles.edit', $muscle) }}"
                                        />
                                        <x-button.default variant="outline"
                                                          icon="trash-2"
                                                          size="3"
                                                          wire:click="deleteItem({{ $muscle->id }})"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="p-8 text-center mb-8">
                            <x-lucide-person-standing class="w-16 h-16 text-muted mx-auto mb-3"/>
                            <p class="text-muted text-sm mb-3">
                                {{ __("You don't have any muscles yet") }}
                            </p>
                            <x-link.default variant="primary" href="{{ route('muscles.create') }}">
                                {{ __('Create Muscle') }}
                            </x-link.default>
                        </div>
                    @endforelse

                </div>

            </x-card>
            <div class="mt-4">
                {{ $muscles->links() }}
            </div>
        </div>

        <x-modal name="delete-muscle" focusable>
            <form wire:submit="confirmDelete" class="p-6">
                <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                    <h2 class="text-xl font-semibold text-secondary">
                        {{ __('Delete muscle') }}
                    </h2>
                    <x-button.default variant="ghost"
                                      type="button"
                                      icon="x"
                                      size="5"
                                      x-on:click="$dispatch('close-modal', 'delete-muscle')"
                    />
                </div>

                <div class="py-4">
                    <div class="flex justify-center mb-4 text-danger">
                        <x-lucide-circle-alert class="h-12 w-12"/>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                        {{ __('Are you sure you want to delete this muscle?') }}
                    </h3>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                    <x-button.default variant="outline"
                                      type="button"
                                      x-on:click="$dispatch('close-modal', 'delete-muscle')"
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

