<x-app-layout :pageTitle="__('Profile')">
    <div class="">
        <x-tab.default default="profile">
            <x-slot:tabs>
                <x-tab.link name="profile">
                    Profile
                </x-tab.link>

                <x-tab.link name="security">
                    Security
                </x-tab.link>

                <x-tab.link name="preferences">
                    Preferences
                </x-tab.link>

                <x-tab.link name="delete">
                    Delete
                </x-tab.link>
            </x-slot:tabs>


            <x-tab.panel name="profile">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-avatar/>
                    <livewire:profile.update-profile-information/>
                </div>
            </x-tab.panel>

            <x-tab.panel name="security">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-password/>

                    <livewire:passkeys/>
                </div>
            </x-tab.panel>

            <x-tab.panel name="preferences">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <livewire:profile.update-appearance/>
                    <livewire:profile.update-language/>
                    <livewire:profile.update-weight/>
                </div>
            </x-tab.panel>

            <x-tab.panel name="delete">
                <livewire:profile.delete-user/>
            </x-tab.panel>

        </x-tab.default>
    </div>
</x-app-layout>
