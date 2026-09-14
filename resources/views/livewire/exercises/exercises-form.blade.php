@php
    $title = $exercise && $exercise->exists
        ? __('Update exercise')
        : __('Create exercise');
@endphp

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card
            :title="$title"
            :description="__('Enter the details below for the exercise.')"
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
                        @elseif ($exercise && $exercise->image_path)
                            <div class="relative w-40 h-40 rounded-lg overflow-hidden border border-border">
                                <img src="{{ Storage::url($exercise->image_path) }}"
                                     class="object-cover w-full h-full"
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
                    <x-form.label for="name" :value="__('Name')"/>
                    <x-form.input id="name" type="text" class="mt-1 block w-full" wire:model="name" required/>
                    <x-form.error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div>
                    <x-form.label for="description" :value="__('Description')"/>
                    <x-form.textarea id="description" type="text" class="mt-1 block w-full" wire:model="description"
                                     required/>
                    <x-form.error :messages="$errors->get('description')" class="mt-2"/>
                </div>

                <div>
                    @include('livewire.exercises.partials.how-to-form', ['allowImageUpload' => true])
                </div>

                <div x-data="{ type: @entangle('type') }">
                    <div class="grid grid-cols-2 gap-2">
                        <label :class="type === 'cardio' ? 'ring-1 ring-secondary border-secondary' : 'border-border'"
                               class="flex items-center gap-3 p-3 rounded-xl border bg-surface cursor-pointer transition">
                            <input type="radio" name="type" value="cardio" x-model="type" required
                                   class="hidden text-primary focus:ring-secondary">
                            <span class="text-primary text-center w-full">Cardio</span>
                        </label>
                        <label :class="type === 'strength' ? 'ring-1 ring-secondary border-secondary' : 'border-border'"
                               class="flex items-center gap-3 p-3 rounded-xl border bg-surface cursor-pointer transition">
                            <input type="radio" name="type" value="strength" x-model="type" required
                                   class="hidden text-primary focus:ring-secondary">
                            <span class="text-primary text-center w-full">{{ __('Strength') }}</span>
                        </label>
                    </div>

                    {{--                    <div x-show="type === 'strength'" x-transition class="space-y-4 mt-4">--}}
                    {{--                        <x-form.select--}}
                    {{--                            id="muscle_group_id"--}}
                    {{--                            name="muscle_group_id"--}}
                    {{--                            wire:model="muscle_group_id"--}}
                    {{--                            required>--}}
                    {{--                            <option value="">{{ __('Select muscle group') }}</option>--}}
                    {{--                            @foreach ($muscleGroups as $group)--}}
                    {{--                                <option value="{{ $group->id }}">{{ $group->name }}</option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </x-form.select>--}}
                    {{--                        <x-form.error :messages="$errors->get('muscle_group_id')" class="mt-2"/>--}}
                    {{--                    </div>--}}
                    <div x-show="type === 'strength'" x-transition class="space-y-6 mt-4">

                        <div>
                            <x-form.label
                                :value="__('Muscle groups')"
                            />

                            <div class="mt-2">
                                <x-form.multi-select
                                    :options="$muscleGroups"
                                    wire:model="selectedMuscleGroups"
                                    :placeholder="__('Select muscle groups...')"
                                />
                            </div>
                        </div>

                        <div>
                            <x-form.label
                                :value="__('Muscles')"
                            />

                            <div class="mt-2">
                                <x-form.multi-select
                                    :options="$muscles"
                                    wire:model="selectedMuscles"
                                    :placeholder="__('Select muscles...')"
                                />
                                @if (count($selectedMuscles))
                                    <div class="mt-4 space-y-3">

                                        <div class="text-xs font-semibold uppercase tracking-wider text-muted">
                                            {{ __('Muscle roles') }}
                                        </div>

                                        @foreach ($selectedMuscles as $muscleId)

                                            @php
                                                $muscle = collect($muscles)
                                                    ->firstWhere('id', $muscleId);
                                            @endphp

                                            @if ($muscle)
                                                <div
                                                    class="flex items-center justify-between gap-4 p-3 rounded-lg bg-surface-hover/50 border border-border"
                                                    wire:key="muscle-role-{{ $muscleId }}"
                                                >

                                                    <div class="font-medium text-primary">
                                                        {{ $muscle['name'] }}
                                                    </div>

                                                    <select
                                                        wire:model.live="muscleRoles.{{ $muscleId }}"
                                                        class="text-sm rounded-md border-border bg-surface text-primary"
                                                    >
                                                        <option value="primary">
                                                            {{ __('Primary') }}
                                                        </option>

                                                        <option value="secondary">
                                                            {{ __('Secondary') }}
                                                        </option>

                                                        <option value="stabilizer">
                                                            {{ __('Stabilizer') }}
                                                        </option>
                                                    </select>

                                                </div>
                                            @endif

                                        @endforeach

                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>


                </div>

                <div class="flex items-center gap-4 pt-4">
                    <x-link.default variant="outline"
                                    href="{{ route('musclegroups.index') }}"
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
    <script>
        function exerciseForm() {
            return {
                type: '{{ old('type', '') }}',
            };
        }

        function imagePreview() {
            return {
                preview: null,

                showPreview(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                clearPreview() {
                    this.preview = null;
                    this.$refs.fileInput.value = '';
                }
            };
        }
    </script>
</div>
