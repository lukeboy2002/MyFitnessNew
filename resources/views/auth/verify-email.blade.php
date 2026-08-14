<x-guest-layout :pageTitle="__('Verify Email')">
    <x-card.default variant="primary">
        <x-slot:description>
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </x-slot:description>


        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button.default variant="primary" class="w-full">
                        {{ __('Resend Verification Email') }}
                    </x-button.default>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-link.default variant="outline"
                                href="{{ route('logout') }}"
                                size="4"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                >Logout
                </x-link.default>
            </form>
        </div>
    </x-card.default>
</x-guest-layout>
