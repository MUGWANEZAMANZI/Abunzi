<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('messages.title') }}</title>

        <!-- Fonts -->

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @endif
    </head>
    <body class="min-h-screen flex flex-col bg-gray-100" lazy>
       <livewire:welcome-controller lazy />
    </body>
</html>
