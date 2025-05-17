<x-action-section>
    <x-slot name="title">
        {{ __('browser.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('browser.description') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('browser.help_text') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-5 space-y-6">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center">
                        <div>
                            @if ($session->agent->isDesktop())
                                <svg ...>...</svg>
                            @else
                                <svg ...>...</svg>
                            @endif
                        </div>

                        <div class="ms-3">
                            <div class="text-sm text-gray-600">
                                {{ $session->agent->platform() ?: __('browser.unknown') }} - {{ $session->agent->browser() ?: __('browser.unknown') }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $session->ip_address }},
                                @if ($session->is_current_device)
                                    <span class="text-green-500 font-semibold">{{ __('browser.this_device') }}</span>
                                @else
                                    {{ __('browser.last_active') }} {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5">
            <x-button wire:click="confirmLogout" wire:loading.attr="disabled">
                {{ __('browser.logout_other') }}
            </x-button>

            <x-action-message class="ms-3" on="loggedOut">
                {{ __('browser.done') }}
            </x-action-message>
        </div>

        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">
                {{ __('browser.confirm_logout_title') }}
            </x-slot>

            <x-slot name="content">
                {{ __('browser.confirm_logout_content') }}

                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                        autocomplete="current-password"
                        placeholder="{{ __('browser.password_placeholder') }}"
                        x-ref="password"
                        wire:model="password"
                        wire:keydown.enter="logoutOtherBrowserSessions" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                    {{ __('browser.cancel') }}
                </x-secondary-button>

                <x-button class="ms-3"
                    wire:click="logoutOtherBrowserSessions"
                    wire:loading.attr="disabled">
                    {{ __('browser.logout_other') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
