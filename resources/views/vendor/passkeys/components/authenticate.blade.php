@include('passkeys::components.partials.authenticateScript')

<form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
    @csrf
</form>

<div onclick="authenticateWithPasskey()">
    @if ($slot->isEmpty())
        <x-button variant="primary" icon="fingerprint-pattern" size="4" class="w-full">

            {{ __('passkeys::passkeys.authenticate_using_passkey') }}
        </x-button>
</div>
@else
    {{ $slot }}
@endif

@if($message = session()->get('authenticatePasskey::message'))
    <div class="mt-2 mb-4 font-medium text-xs text-error flex gap-2 items-center">
        <div class="flex gap-2 items-center">
            <x-lucide-triangle-alert class="h-4 w-4"/>
            {{ $message }}
        </div>
    </div>
@endif
<x-divider>Or Continue with</x-divider>
