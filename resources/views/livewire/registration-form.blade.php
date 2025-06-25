<div>
<form wire:submit.prevent="register">
    <x-validation-errors class="mb-4" />

    <div class="mt-4">
        <x-label for="name" value="Name" />
        <x-input id="name" type="text" class="block mt-1 w-full" wire:model.defer="name" required />
    </div>

    <div class="mt-4">
        <x-label for="identification" value="ID Number" />
        <x-input id="identification" type="text" class="block mt-1 w-full" wire:model.defer="identification" required />
    </div>

    <div class="mt-4">
        <x-label for="email" value="Email" />
        <x-input id="email" type="email" class="block mt-1 w-full" wire:model.defer="email" required />
    </div>

    <div class="mt-4">
        <x-label for="phone" value="Phone" />
        <x-input id="phone" type="text" class="block mt-1 w-full" wire:model.defer="phone" required />
    </div>

    <div class="mt-4">
        <x-label for="password" value="Password" />
        <x-input id="password" type="password" class="block mt-1 w-full" wire:model.defer="password" required />
    </div>

    <div class="mt-4">
        <x-label for="password_confirmation" value="Confirm Password" />
        <x-input id="password_confirmation" type="password" class="block mt-1 w-full" wire:model.defer="password_confirmation" required />
    </div>

    <div class="mt-4">
        <x-label for="passcode" value="Chief/Cell Passcode (optional)" />
        <x-input id="passcode" type="text" class="block mt-1 w-full" wire:model.defer="passcode" />
    </div>

    @if ($level)
        <div class="mt-4">
            <x-label for="level_name" :value="'Enter your ' . ucfirst($level)" />
            <x-input id="levelName" type="text" class="block mt-1 w-full" wire:model.defer="levelName" />
        </div>
    @endif

    

    <div class="mt-4">
        <x-button>{{ __('Register') }}</x-button>
    </div>
</form>

</div>
