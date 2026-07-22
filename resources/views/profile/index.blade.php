<x-app-layout :pageTitle="__('Profile')">
    <div class="">
        <x-tabs default="profile">
            <x-slot:tabs>
                <x-link-tab name="profile">
                    Profile
                </x-link-tab>

                <x-link-tab name="security">
                    Security
                </x-link-tab>

                <x-link-tab name="preferences">
                    Preferences
                </x-link-tab>

                <x-link-tab name="delete">
                    Delete
                </x-link-tab>
            </x-slot:tabs>


            <x-tab-panel name="profile">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-avatar/>
                    <livewire:profile.update-profile-information/>
                </div>
            </x-tab-panel>

            <x-tab-panel name="security">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-password/>

                    <livewire:passkeys/>
                </div>
            </x-tab-panel>

            <x-tab-panel name="preferences">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-appearance/>
                    <livewire:profile.update-language/>
                    <livewire:profile.update-weight/>
                </div>
            </x-tab-panel>

            <x-tab-panel name="delete">
                <livewire:profile.delete-user/>
            </x-tab-panel>

        </x-tabs>
    </div>
</x-app-layout>
