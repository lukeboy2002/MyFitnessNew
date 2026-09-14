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
            <x-card title="{{ __('Bodyparts') }}" description="{{ __('All available bodyparts') }}">
                <x-slot:actions>
                    <div class="flex items-center justify-end pb-1 mb-2 gap-2">
                        <x-link.default variant="outline"
                                        icon="square-plus"
                                        size="3"
                                        href="{{ route('bodyparts.create') }}"
                        />
                    </div>
                </x-slot:actions>

                <div class="grid {{ $bodyparts->isEmpty() ? 'grid-cols-1' : 'grid-cols-1 md:grid-cols-2' }} gap-3">

                    @forelse ($bodyparts as $bodypart)
                        <div class="border border-border bg-surface-secondary rounded-md shadow-sm flex gap-4">
                            <div class="min-h-24 w-1/3 flex items-center justify-center">
                                <img src="{{ $bodypart->image_path }}" alt="{{ $bodypart->name }}"
                                     class="w-auto h-24 object-cover rounded-l-md">
                            </div>

                            <div class="flex flex-col py-2 pr-2.5 w-full">
                                <div class="text-secondary flex flex-1 justify-between">
                                    <div class="text-xl text-secondary">{{ $bodypart->name }}
                                    </div>

                                    <div class="flex item-center justify-end gap-2 mt-auto">
                                        <x-link.default variant="outline"
                                                        icon="edit-2"
                                                        size="3"
                                                        href="{{ route('bodyparts.edit', $bodypart) }}"
                                        />
                                        <x-button.default variant="outline"
                                                          icon="trash-2"
                                                          size="3"
                                                          wire:click="deleteItem({{ $bodypart->id }})"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="p-8 text-center mb-8">
                            <x-lucide-person-standing class="w-16 h-16 text-muted mx-auto mb-3"/>
                            <p class="text-muted text-sm mb-3">
                                {{ __("You don't have any bodyparts yet") }}
                            </p>
                            <x-link.default variant="primary" href="{{ route('bodyparts.create') }}">
                                {{ __('Create Bodypart') }}
                            </x-link.default>
                        </div>
                    @endforelse

                </div>

            </x-card>
        </div>

        <x-modal name="delete-bodypart" focusable>
            <form wire:submit="confirmDelete" class="p-6">
                <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
                    <h2 class="text-xl font-semibold text-secondary">
                        {{ __('Delete bodypart') }}
                    </h2>
                    <x-button.default variant="ghost"
                                      type="button"
                                      icon="x"
                                      size="5"
                                      x-on:click="$dispatch('close-modal', 'delete-bodypart')"
                    />
                </div>

                <div class="py-4">
                    <div class="flex justify-center mb-4 text-danger">
                        <x-lucide-circle-alert class="h-12 w-12"/>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                        {{ __('Are you sure you want to delete this bodypart?') }}
                    </h3>
                </div>

                <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
                    <x-button.default variant="outline"
                                      type="button"
                                      x-on:click="$dispatch('close-modal', 'delete-bodypart')"
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

