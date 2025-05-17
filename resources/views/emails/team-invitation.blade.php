@component('mail::message')
{{ __('jetstream.invited_to_team', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('jetstream.create_account_intro') }}

@component('mail::button', ['url' => route('register')])
{{ __('jetstream.create_account_button') }}
@endcomponent

{{ __('jetstream.accept_invitation_existing') }}

@else
{{ __('jetstream.accept_invitation_only') }}
@endif

@component('mail::button', ['url' => $acceptUrl])
{{ __('jetstream.accept_invitation_button') }}
@endcomponent

{{ __('jetstream.ignore_invitation') }}
@endcomponent
