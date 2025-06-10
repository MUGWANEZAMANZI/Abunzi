<div>
     <header class="flex justify-between items-center p-1 bg-blue-600 text-white shadow-md">
            <h1 class="text-2xl font-bold">{{ __('messages.title_header') }}</h1>
            <nav class="flex gap-4 items-center">
                <a class="hover:bg-blue-500 px-4 py-2 rounded-md" href="{{ route('login') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 12H9m0 0l3.75-3.75M9 12l3.75 3.75" />
                    </svg>
                    {{ __('messages.login') }}
                </a>
                <a class="hover:bg-blue-500 px-4 py-2 rounded-md" href="{{ route('register') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a7.5 7.5 0 0115 0m1.5-6h-3m0 0v-3m0 3v3" />
                    </svg>
                    {{ __('messages.register') }}
                </a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('set-locale', 'rw') }}" class="hover:bg-blue-500 px-2 py-1 rounded-md">
                <img src="{{ asset('kiny.svg') }}" alt="{{ __('messages.kinyarwanda') }}" class="h-5 w-5">
            </a>
            <a href="{{ route('set-locale', 'en') }}" class="hover:bg-blue-500 px-2 py-1 rounded-md">
                <img src="{{ asset('uk.svg') }}" alt="{{ __('messages.english') }}" class="h-5 w-5">
            </a>

                </div>
            </nav>
        </header>
        <main class="flex-1 p-10 bg-white shadow-md">
            <section class="text-center">
                <h2 class="text-4xl font-bold text-blue-600 mb-4">{{ __('messages.welcome') }}</h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    {{ __('messages.description') }}
                </p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md text-lg font-semibold hover:bg-indigo-700">
                        {{ __('messages.start_using') }}
                    </a>
                </div>
            </section>
            <section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow">
                    <h3 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('messages.choose_mediators') }}</h3>
                    <p class="text-gray-700">
                        {{ __('messages.choose_mediators_desc') }}
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow">
                    <h3 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('messages.how_system_works') }}</h3>
                    <p class="text-gray-700">
                        {{ __('messages.how_system_works_desc') }}
                    </p>
                </div>
            </section>
            <div class="mt-20">
                <livewire:aimbaza lazy/>
            </div>
        </main>
        
        <footer class="bg-blue-600 text-white text-center py-4">
            <p>{{ __('messages.footer') }}</p>
        </footer>
</div>
