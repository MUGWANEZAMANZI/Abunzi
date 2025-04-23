<div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-blue-600 py-6 flex justify-between px-3">
        <h1 class="text-3xl font-bold text-white text-center inline-block">Dashboard</h1>
        <button class="m-2 p-2 bg-red-400 hover:bg-red-600 rounded-md">Sohoka</button>
    </div>

    <!-- Main Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Past Cases Section -->
                <div class="bg-white shadow-xl sm:rounded-lg p-6">
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
                <div class="bg-white shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Ikirego gishya</h2>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="case-title" class="block text-sm font-medium text-gray-700">Umutwe</label>
                            <input type="text" id="case-title" name="case_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="case-description" class="block text-sm font-medium text-gray-700">Sobanura</label>
                            <textarea id="case-description" name="case_description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="case-file" class="block text-sm font-medium text-gray-700">Ikimenyetso (Singombwa)</label>
                            <input type="file" id="case-file" name="case_file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="case-witnesses" class="block text-sm font-medium text-gray-700">Abatangabuhanya (Singombwa)</label>
                            <textarea id="case-witnesses" name="case_witnesses" rows="2" placeholder="Andika amazina na telephone byumutanga buhamya" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Rega
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Button -->
    <button class="fixed bottom-4 right-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Ask AI
    </button>
</div>
</div>