<div>
    <x-app-layout>
        <div class="max-w-4xl mx-auto p-6">
            <!-- Profile Card -->
            <div class="bg-white shadow-xl rounded-lg p-6 mb-8 flex flex-col md:flex-row items-center">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-8">
                    
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">
                        {{ $user->name }}
                    </h2>
                    <p class="text-gray-600 mb-4">{{ $user->email }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- <div>
                            <span class="text-blue-700 font-semibold">ID:</span>
                            <span>{{ $user->id }}</span>
                        </div> --}}
                        <div>
                            <span class="text-blue-700 font-semibold">Phone:</span>
                            <span>{{ $user->phone }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-semibold">Identification:</span>
                            <span>{{ $user->identification }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-semibold">Role:</span>
                            <span>{{ $user->role }}</span>
                        </div>
                    
                        <div>
                            <span class="text-blue-700 font-semibold">Created At:</span>
                            <span>{{ $user->created_at }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-semibold">Updated At:</span>
                            <span>{{ $user->updated_at }}</span>
                        </div>

                        <div class="text-blue-700 font-semibold">
                            <span>Last Login:</span>
                            <span> {{ $lastActivity ?? 'Never' }} </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disputes Section -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Disputes Raised</h3>
                @forelse ($disputes as $dispute)
                    <div class="mb-4 p-4 border-l-4 border-blue-500 bg-blue-50 rounded">
                        <h4 class="font-semibold text-lg text-blue-800">{{ $dispute->title }}</h4>
                        <p class="text-gray-700 mb-2">{{ $dispute->description }}</p>
                        <div class="flex flex-wrap text-xs text-gray-500 gap-4">
                            <span>Status: <span class="font-semibold text-blue-700">{{ $dispute->status }}</span></span>
                            <span>Created At: {{ $dispute->created_at }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No disputes raised.</p>
                @endforelse
            </div>
        </div>
    </x-app-layout>
</div>
