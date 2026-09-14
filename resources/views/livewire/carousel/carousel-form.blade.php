@php
    $title = $carousel && $carousel->exists
        ? __('Update Carousel Item')
        : __('Create Carousel Item');
@endphp

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card
            :title="$title"
            :description="__('Enter the details below for the carousel item.')"
            class="p-6"
        >

            <form wire:submit="save" class="space-y-6">
                <!-- Afbeelding -->
                <div>
                    <x-form.label for="image_path" :value="__('Image')"/>

                    <div class="mt-2 flex items-center gap-4">
                        @if ($image_path)
                            <div class="relative w-40 h-40 rounded-lg overflow-hidden border border-border">
                                <img src="{{ $image_path->temporaryUrl() }}" class="object-cover w-full h-full"
                                     alt="Image preview">
                            </div>
                        @elseif ($carousel && $carousel->image_path)
                            <div class="relative w-40 h-40 rounded-lg overflow-hidden border border-border">
                                <img src="{{ Storage::url($carousel->image_path) }}" class="object-cover w-full h-full"
                                     alt="Carousel image">
                            </div>
                        @else
                            <div
                                class="w-40 h-40 rounded-lg border-2 border-dashed border-border flex items-center justify-center text-muted">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2">
                        <x-form.input type="file" wire:model="image_path" id="image_path" class="mt-2 w-full text-sm text-muted
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-surface file:text-primary
                                hover:file:bg-surface-hover
                                cursor:pointer "

                        />
                        <p class="text-xs text-muted">{{ __('JPG, PNG, WebP, HEIC. Max 10MB.') }}</p>
                        <x-form.error :messages="$errors->get('image_path')" class="mt-2"/>
                    </div>


                    <div wire:loading wire:target="image_path" class="mt-2 text-sm text-secondary">
                        {{ __('Uploaden...') }}
                    </div>
                </div>

                <div>
                    <x-form.label for="author" :value="__('Author')"/>
                    <x-form.input id="author" type="text" class="mt-1 block w-full" wire:model="author" required
                                  autofocus/>
                    <x-form.error :messages="$errors->get('author')" class="mt-2"/>
                </div>

                <!-- Link -->
                <div>
                    <x-form.label for="link" :value="__('Link (optioneel)')"/>
                    <x-form.input id="link" type="text" class="mt-1 block w-full" wire:model.blur="link"
                                  placeholder="https://..."/>
                    <x-form.error :messages="$errors->get('link')" class="mt-2"/>
                </div>

                <!-- Actief -->
                <div class="flex items-center gap-2">
                    <x-form.checkbox id="is_active" wire:model="is_active"/>
                    <x-form.label for="is_active" :value="__('Active')" class="pb-0"/>
                    <x-form.error :messages="$errors->get('is_active')" class="mt-2"/>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <x-link.default variant="outline"
                                    href="{{ route('carousel.index') }}"
                                    class="w-full">
                        {{ __('Cancel') }}
                    </x-link.default>

                    <x-button.default variant="primary"
                                      class="w-full"
                                      wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button.default>

                    <div wire:loading wire:target="save" class="text-sm text-muted">
                        {{ __('Saving') }}
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</div>
