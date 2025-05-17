<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('verify.phone') }}">
            @csrf

            <div>
                <x-label for="code" value="{{ __('Verification Code') }}" />
                <x-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Verify') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>