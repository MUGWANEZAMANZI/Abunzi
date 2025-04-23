<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Abunzi - Digital local disputes resolution platform</title>

        <!-- Fonts -->
        

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
          
        @endif
    </head>
    <body class="min-h-screen flex flex-col bg-gray-100">
        <header class="flex justify-between items-center p-6 bg-blue-600 text-white shadow-md">
            <h1 class="text-3xl font-bold">Abunzi</h1>
            <nav class="flex gap-4 items-center">
            <a class="hover:bg-blue-500 px-4 py-2 rounded-md" href="{{ route('login') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M18 12H9m0 0l3.75-3.75M9 12l3.75 3.75" />
                </svg>
                Injira
            </a>
            <a class="hover:bg-blue-500 px-4 py-2 rounded-md" href="{{ route('register') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.25a7.5 7.5 0 0115 0m1.5-6h-3m0 0v-3m0 3v3" />
                </svg>
                Iyandikishe
            </a>
            <div class="flex items-center gap-2">
                <a href="" class="hover:bg-blue-500 px-2 py-1 rounded-md">
                <img src="{{ asset('kiny.svg') }}" alt="Rwanda Flag" class="h-5 w-5">
                </a>
                <a href="" class="hover:bg-blue-500 px-2 py-1 rounded-md">
                <img src="{{ asset('uk.svg') }}" alt="UK Flag" class="h-5 w-5">
                </a>
            </div>
            </nav>
        </header>
        <main class="flex-1 p-10 bg-white shadow-md">
            <section class="text-center">
                <h2 class="text-4xl font-bold text-blue-600 mb-4">Urakaza neza!</h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    Abunzi ni urubuga rwihuse, rwizewe kandi rwizewe rwo gukemura ibibazo by'abaturage binyuze mu bwinzi bw'Inyangamugayo.
                    Tanga ikirego, ukurikire uko gikemurwa, kandi ubone amakuru yihariye ku kibazo cyawe.
                </p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md text-lg font-semibold hover:bg-indigo-700">
                        Tangira gukoresha sisitemu
                    </a>
                </div>
            </section>
            <section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow">
                    <h3 class="text-2xl font-semibold text-blue-600 mb-2">Hitamo Abunzi?</h3>
                    <p class="text-gray-700">
                        Abunzi ni urubuga rwihuse, rwizewe kandi rwizewe rwo gukemura ibibazo.
                        Uru rubuga rwacu rwizeza ubunyangamugayo no kugera kuri bose.
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow">
                    <h3 class="text-2xl font-semibold text-blue-600 mb-2">Uko Sisitemu ikora</h3>
                    <p class="text-gray-700">
                        Iyandikishe, ohereza ikibazo cyawe, hanyuma sisitemu izagufasha mu gukemura ikibazo.
                        Uzakomeze ube mu makuru uko ikibazo cyawe gikemurwa. Ikibazo kinaniranye kizamurwa mu ku murenge, mu rukiko otomatike.
                    </p>
                </div>
            </section>
        </main>
        <footer class="bg-blue-600 text-white text-center py-4">
            <p>&copy; {{ Date('Y') }} Abunzi. Uburenganiza burubahirizwa.</p>
        </footer>
    </body>
</html>
