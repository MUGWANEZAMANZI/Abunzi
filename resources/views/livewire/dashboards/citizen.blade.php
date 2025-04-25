<div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-blue-600 py-1 flex justify-between px-3">
        <h1 class="text-3xl font-bold text-white text-center inline-block">Dashboard</h1>
        <div class="ms-3 relative">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </button>
                    @else
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                {{ Auth::user()->name }}

                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </span>
                    @endif
                </x-slot>

                <x-slot name="content">
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                            {{ __('API Tokens') }}
                        </x-dropdown-link>
                    @endif

                    <div class="border-t border-gray-200 dark:border-gray-600"></div>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-dropdown-link href="{{ route('logout') }}"
                                 @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="flex md:grid-cols-2 gap-6">
                <!-- Past Cases Section -->
                <div class="bg-white w-4/5 shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Ibirego byanjye</h2>
                    <ul class="space-y-4">
                        <!-- Example Case -->
                        <li class="p-4 bg-gray-100 rounded shadow">
                            <h3 class="text-lg font-semibold">Ikirego nimero #12345</h3>
                            <p class="text-sm text-gray-600">Cyatanzwe: 2023-01-15</p>
                            <p class="text-sm text-gray-600">Uko gihagaze: Cyararangiye</p>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                                Bika umwanzuro wawe
                            </button>
                        </li>
                        <li class="p-4 bg-gray-100 rounded shadow">
                            <h3 class="text-lg font-semibold">Ikirego nimero #67890</h3>
                            <p class="text-sm text-gray-600">Cyatanzwe: 2023-02-10</p>
                            <p class="text-sm text-gray-600">Uko gihagaze: Ntikirakorwa</p>
                        </li>
                        <!-- Add more cases dynamically -->
                    </ul>
                </div>

                <!-- Create New Case Section -->
                <div class="w-1/5 h-10 shadow-xl sm:rounded-lg">
                <button class="p-2 w-full flex items-center justify-center rounded-md bg-green-400 hover:bg-green-900 text-white font-bold"
                    wire:click="openDisputeModal()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ikirego Gishya
            </button>
            </div>
        </div>
        @if()
        <livewire:ikirego />
    </div>

    <livewire:aimbaza />
</div>
</div>