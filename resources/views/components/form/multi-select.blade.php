@props([
    'options' => [],
    'selected' => [],
    'placeholder' => __('Select options...'),
])

<div
    x-data="{
        options: @js($options),
        selected: @entangle($attributes->wire('model')),
        search: '',
        open: false,
        get filteredOptions() {
            return this.options.filter(option => {
                const isSelected = this.selected.includes(option.id) || this.selected.includes(option.id.toString());
                const matchesSearch = option.name.toLowerCase().includes(this.search.toLowerCase());
                return !isSelected && matchesSearch;
            });
        },
        get selectedOptions() {
            return this.options.filter(option => this.selected.includes(option.id) || this.selected.includes(option.id.toString()));
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.searchInput.focus());
            }
        },
        select(id) {
            if (!this.selected.includes(id)) {
                this.selected.push(id);
            }
            this.search = '';
            this.open = false;
        },
        remove(id) {
            this.selected = this.selected.filter(i => i != id);
        }
    }"
    class="relative"
    @click.away="open = false"
>
    <!-- Geselecteerde Items (Badges) -->
    <div class="flex flex-wrap gap-2 mb-2">
        <template x-for="option in selectedOptions" :key="option.id">
            <span
                class="inline-flex items-center px-2 py-1 rounded-md bg-secondary/20 border border-secondary text-secondary text-xs font-medium">
                <template x-if="option.image">
                    <img :src="option.image" class="w-4 h-4 rounded-full mr-1 object-cover">
                </template>
                <span x-text="option.name"></span>
                <button
                    type="button"
                    @click="remove(option.id)"
                    class="ml-1 focus:outline-none hover:text-white"
                >
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </button>
            </span>
        </template>
    </div>

    <!-- Selectveld/Trigger -->
    <div
        @click="toggle()"
        class="bg-surface border border-border rounded-md text-primary text-sm cursor-pointer flex items-center justify-between px-3 py-2.5 shadow-xs"
        :class="open ? 'border-secondary ring-0' : ''"
    >
        <span x-show="selected.length === 0" class="text-muted" x-text="'{{ $placeholder }}'"></span>
        <span x-show="selected.length > 0" class="text-primary">{{ __('Select more...') }}</span>

        <svg class="h-5 w-5 text-muted transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full rounded-md bg-surface border border-border shadow-lg"
        style="display: none;"
    >
        <div class="p-2 border-b border-border">
            <input
                x-ref="searchInput"
                x-model="search"
                type="text"
                class="w-full bg-surface-hover border border-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-secondary"
                placeholder="{{ __('Search...') }}"
                @keydown.escape="open = false"
            >
        </div>

        <ul class="max-h-60 overflow-auto py-1 text-sm">
            <template x-for="option in filteredOptions" :key="option.id">
                <li
                    @click="select(option.id)"
                    class="cursor-pointer select-none px-4 py-2 hover:bg-secondary hover:text-white transition-colors flex items-center"
                >
                    <template x-if="option.image">
                        <img :src="option.image" class="w-6 h-6 rounded-full mr-2 object-cover" alt="">
                    </template>
                    <span x-text="option.name"></span>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-4 py-2 text-muted italic">
                {{ __('No options found') }}
            </li>
        </ul>
    </div>
</div>
