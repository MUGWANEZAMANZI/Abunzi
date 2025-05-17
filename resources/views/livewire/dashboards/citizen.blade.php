<div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="bg-blue-600 py-2 sm:py-1 flex justify-between items-center px-4 sm:px-3">
            <h1 class="text-xl sm:text-3xl font-bold text-white text-center inline-block">Dashboard</h1>
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
        <div class="py-6 sm:py-12">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <!-- Create New Case Section for Mobile -->
                    <div class="sm:hidden w-full">
                        <form wire:submit.prevent="openModal">
                            <button class="p-3 w-full flex items-center justify-center rounded-md bg-green-400 hover:bg-green-900 text-white font-bold"
                                type="submit"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                {{ __('citizen.title') }}
                            </button>
                        </form>
                    </div>

                    <!-- Past Cases Section -->
                    <div class="bg-white w-full sm:w-6/7 shadow-xl rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-700 mb-4">Ibirego byanjye</h2>
                       
                        <livewire:ibirego-byanjye />
                       
                    </div>

                    <!-- Create New Case Section for Desktop -->
                    <div class="hidden sm:block w-1/7 h-10 shadow-xl rounded-lg">
                        <form wire:submit.prevent="openModal">
                            <button class="p-1 text-sm w-full flex items-center justify-center rounded-md bg-green-400 hover:bg-green-900 text-white font-bold"
                                type="submit"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                {{ __('citizen.title') }}
                            </button>
                        </form>
                    </div>
                </div>
                @if($open)
                    <livewire:ikirego />
                @endif
            </div>
        </div>

        <livewire:aimbaza />
    </div>
</div>