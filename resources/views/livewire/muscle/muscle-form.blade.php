@php
    $title = $muscle && $muscle->exists
        ? __('Update muscle')
        : __('Create muscle');
@endphp

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card
            :title="$title"
            :description="__('Enter the details below for the muscle.')"
            class="p-6"
        >

            <form wire:submit="save" class="space-y-6">
                <div>
                    <x-form.label for="name" :value="__('Name')"/>
                    <x-form.input id="name" type="text" class="mt-1 block w-full" wire:model="name" required/>
                    <x-form.error :messages="$errors->get('name')" class="mt-2"/>
                </div>
                <div x-data="{
                        open: false,
                        selected: @entangle('muscle_group_id'),
                        selectedName: '',
                        selectedImage: '',

                        select(id, name, image) {
                            this.selected = id;
                            this.selectedName = name;
                            this.selectedImage = image;
                            this.open = false;
                        }
                    }"
                     x-init="
                        const groups = @js(
                            $bodyParts
                                ->flatMap(fn ($bodyPart) => $bodyPart->muscleGroups)
                                ->keyBy('id')
                        );

                        if (selected && groups[selected]) {
                            selectedName = groups[selected].name;
                            selectedImage = groups[selected].image_path
                                ? '{{ asset('storage') }}/' + groups[selected].image_path
                                : '';
                        }
                    "
                     class="relative">
                    <x-form.label :value="__('Muscle group')"/>

                    {{-- Selected muscle group --}}
                    <button type="button"
                            @click="open = !open"
                            class="flex w-full items-center justify-between rounded-md border border-border bg-surface px-3 py-2.5 text-sm text-primary focus:border-secondary focus:ring-0"
                    >
                        <div class="flex items-center gap-3">
                            <template x-if="selectedImage">
                                <img :src="selectedImage"
                                     alt=""
                                     class="h-8 w-8 object-contain"
                                >
                            </template>

                            <template x-if="!selectedImage">
                                <div class="h-8 w-8"></div>
                            </template>

                            <span
                                x-text="selectedName || 'Select muscle group'"
                            ></span>
                        </div>

                        <x-lucide-chevron-down class="h-4 w-4 text-muted"/>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open"
                         @click.outside="open = false"
                         x-transition
                         class="absolute z-50 mt-1 max-h-96 w-full overflow-y-auto rounded-md border border-border bg-surface shadow-lg"
                    >
                        @foreach($bodyParts as $bodyPart)

                            {{-- BodyPart --}}
                            <div class="sticky top-0 border-b border-border bg-surface px-3 py-2">
                                <div class="text-xs font-semibold uppercase tracking-wide text-muted">
                                    {{ $bodyPart->name }}
                                </div>
                            </div>

                            {{-- Muscle Groups --}}
                            @foreach($bodyPart->muscleGroups as $group)

                                <button type="button"
                                        @click="select(
                                            {{ $group->id }},
                                            @js($group->name),
                                            @js(
                                                $group->image_path
                                                    ? asset('storage/' . $group->image_path)
                                                    : ''
                                            )
                                        )"
                                        class="flex w-full items-center gap-3 px-4 py-2.5 text-left hover:bg-surface-hover"
                                >
                                    @if($group->image_path)
                                        <img
                                            src="{{ asset('storage/' . $group->image_path) }}"
                                            alt="{{ $group->name }}"
                                            class="h-8 w-8 shrink-0 object-contain">
                                    @else
                                        <div class="h-8 w-8 shrink-0"></div>
                                    @endif
                                    <span class="text-sm text-primary">
                                        {{ $group->name }}
                                    </span>
                                </button>
                            @endforeach
                        @endforeach
                    </div>
                    <x-form.error :messages="$errors->get('muscle_group_id')" class="mt-2"/>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <x-link.default variant="outline"
                                    href="{{ route('muscles.index') }}"
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
