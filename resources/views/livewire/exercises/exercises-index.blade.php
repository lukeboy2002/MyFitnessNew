<div>

    <div class="px-4 pt-6">
        <x-card variant="ghost" title="{{ __('Exercises') }}" description="{{ __('All available exercises') }}">
            <x-slot:actions>

                <div class="flex items-center justify-end pb-1 mb-2 gap-2">
                    <x-form.search icon="search" type="search" wire:model.live.debounce.300ms="search"
                                   placeholder="{{ __('Search') }}..."
                                   class="w-full"/>

                    <x-dropdown.default align="right" width="48">
                        <x-slot name="trigger">
                            <x-button.default variant="outline"
                                              icon="filter"
                                              size="4"
                            />
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown.button wire:click="$set('muscleGroupId', '')">
                                {{ __('All muscle groups') }}
                            </x-dropdown.button>

                            @foreach ($muscleGroups as $group)
                                <x-dropdown.button wire:key="filter-musclegroup-{{ $group->id }}"
                                                   wire:click="$set('muscleGroupId', '{{ $group->id }}')">
                                    {{ $group->name }}
                                </x-dropdown.button>
                            @endforeach
                        </x-slot>
                    </x-dropdown.default>

                    <x-link.default variant="outline"
                                    icon="square-plus"
                                    size="4"
                                    href="{{ route('exercises.create') }}"
                    />
                </div>
            </x-slot:actions>

            <div
                class="grid {{ $exercises->isEmpty() ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' }} gap-3">

                @forelse ($exercises as $exercise)
                    <div wire:key="exercise-{{ $exercise->id }}"
                         class="border border-border bg-surface-secondary rounded-md shadow-sm flex gap-4 px-2 hover:bg-surface-hover">

                        <a href="{{ route('exercises.show', $exercise->slug) }}"
                           class="flex flex-1 gap-4 min-w-0 py-2">

                            {{-- Image --}}
                            <div class="flex items-center w-1/6">
                                @if ($exercise->image_path)
                                    <img src="{{ asset('storage/' . $exercise->image_path) }}"
                                         alt="{{ $exercise->name }}"
                                         class="object-cover w-14 rounded-full">
                                @else
                                    @if ($exercise->isCardio())
                                        <x-lucide-heart-pulse class="h-12 w-12 text-secondary"/>
                                    @else
                                        <x-lucide-biceps-flexed class="h-12 w-12 text-secondary"/>
                                    @endif
                                @endif
                            </div>

                            {{-- Exercise information --}}
                            <div class="flex flex-col justify-center py-2 flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="text-md text-secondary">
                                        {{ $exercise->name }}
                                    </div>
                                    @if ($exercise->user_id)
                                        <x-badge.default variant="exercise">
                                            {{ __('Custom') }}
                                        </x-badge.default>
                                    @endif
                                </div>

                                @if ($exercise->muscleGroups->isNotEmpty())
                                    <div class="mt-1 text-muted flex gap-1 items-center">
                                        <x-lucide-biceps-flexed class="h-3 w-3"/>
                                        @foreach ($exercise->muscleGroups as $muscleGroup)
                                            <span
                                                class="text-xs border-r border-r-muted pr-1 last:border-0">
                                                {{ $muscleGroup->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </a>

                        {{-- Actions --}}
                        @if (auth()->user()?->can('update', $exercise) || auth()->user()?->can('delete', $exercise))
                            <div class="flex items-center gap-2 pr-2">
                                @can('update', $exercise)
                                    <x-link.default
                                        variant="outline"
                                        icon="edit-2"
                                        size="3"
                                        href="{{ route('exercises.edit', $exercise->slug) }}"
                                    />
                                @endcan

                                @can('delete', $exercise)
                                    <x-button.default
                                        variant="outline"
                                        icon="trash-2"
                                        size="3"
                                        wire:click="deleteItem({{ $exercise->id }})"
                                    />
                                @endcan
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="p-8 text-center mb-8">
                        <x-lucide-activity class="w-16 h-16 text-muted mx-auto mb-3"/>
                        <p class="text-muted text-sm mb-3">
                            {{ __("You don't have any exercises yet") }}
                        </p>
                        <x-link.default variant="primary" href="{{ route('musclegroups.create') }}">
                            {{ __('Create exercices') }}
                        </x-link.default>
                    </div>
                @endforelse
            </div>

        </x-card>
        @if ($hasMore)
            <div
                x-data
                x-intersect="$wire.loadMore()"
                wire:key="infinite-scroll-sentinel"
                class="flex justify-center py-6"
            >
                <svg
                    wire:loading
                    wire:target="loadMore"
                    class="h-6 w-6 animate-spin"
                    style="color: hsl(20.5 90.2% 48.2%)"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                    ></path>
                </svg>
            </div>
        @endif
        {{--        <div class="mt-4">--}}
        {{--            {{ $exercises->links() }}--}}
        {{--        </div>--}}
    </div>

    <x-modal name="delete-exercice" focusable>
        <form wire:submit="confirmDelete" class="p-6">
            <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                <h2 class="text-xl font-semibold text-secondary">
                    {{ __('Delete exercise') }}
                </h2>
                <x-button.default variant="ghost"
                                  type="button"
                                  icon="x"
                                  size="5"
                                  x-on:click="$dispatch('close-modal', 'delete-exercice')"
                />
            </div>

            <div class="py-4">
                <div class="flex justify-center mb-4 text-danger">
                    <x-lucide-circle-alert class="h-12 w-12"/>
                </div>
                <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                    {{ __('Are you sure you want to delete this exercise?') }}
                </h3>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                <x-button.default variant="outline"
                                  type="button"
                                  x-on:click="$dispatch('close-modal', 'delete-exercice')"
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

