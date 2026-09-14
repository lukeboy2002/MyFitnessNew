<x-modal name="delete-exercise" focusable>
    <form wire:submit="confirmDelete" class="p-6">
        <div class="flex items-center justify-between mb-4 border-b border-border pb-4">
            <h2 class="text-xl font-semibold text-secondary">
                {{ __('Delete Exercise from Workout') }}
            </h2>

            <x-button.default
                variant="ghost"
                type="button"
                icon="x"
                size="5"
                x-on:click="$dispatch('close-modal', 'delete-exercise')"
            />
        </div>

        <div class="py-4">
            <div class="flex justify-center mb-4 text-error">
                <x-lucide-circle-alert class="h-12 w-12"/>
            </div>

            <h3 class="mb-5 text-lg font-normal text-center text-primary-muted">
                {{ __('Are you sure you want to delete this exercise from this workout?') }}
            </h3>
        </div>

        <div class="mt-6 flex justify-end gap-2 border-t border-border pt-4">
            <x-button.default
                variant="outline"
                type="button"
                x-on:click="$dispatch('close-modal', 'delete-exercise')"
                class="w-full"
            >
                {{ __('No') }}
            </x-button.default>

            <x-button.default
                type="submit"
                variant="primary"
                class="w-full"
            >
                {{ __('Yes') }}
            </x-button.default>
        </div>
    </form>
</x-modal>
