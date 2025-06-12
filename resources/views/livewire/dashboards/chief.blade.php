<div>
    <x-app-layout>
        {{-- <x-slot name="header">    
            <div class="mt-4 text-center">
                <h1 class="text-2xl font-bold text-red-500">Saranganya ibirego</h1>
            </div>
        </x-slot> --}}

        <!-- Tabs -->
        <div class="mb-4 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center items-center w-full">
                <li class="mr-2">
                <button wire:click="setActiveTab('all')" 
                    class="inline-block p-4 {{ $activeTab === 'all' ? 'text-red-600 border-b-2 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">
                {{ __('chief.all') }} ({{ $disputeCounts['all'] }})
            </button>
        </li>
        <li class="mr-2">
            <button wire:click="setActiveTab('received')" 
                    class="inline-block p-4 {{ $activeTab === 'received' ? 'text-red-600 border-b-2 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">
                {{ __('chief.received') }} ({{ $disputeCounts['received'] }})
            </button>
        </li>
        <li class="mr-2">
            <button wire:click="setActiveTab('assigned')" 
                    class="inline-block p-4 {{ $activeTab === 'assigned' ? 'text-red-600 border-b-2 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">
                {{ __('chief.assigned') }} ({{ $disputeCounts['assigned'] }})
            </button>
        </li>
        <li class="mr-2">
            <button wire:click="setActiveTab('resolved')" 
                    class="inline-block p-4 {{ $activeTab === 'resolved' ? 'text-red-600 border-b-2 border-red-600' : 'hover:text-gray-600 hover:border-gray-300' }}">
                {{ __('chief.solved') }} ({{ $disputeCounts['resolved'] }})
            </button>
        </li>

        {{-- Filler to push search to the right --}}
        <li class="flex-1"></li>

        {{-- Livewire Search with full width --}}
        <li class="flex-1 ml-auto mt-2 mr-2">
            <livewire:search />
        </li>
            </ul>
        </div>


        <!-- Main container with responsive flex -->
        <div class="flex flex-col md:flex-row gap-4 px-2">
            <!-- Table container with horizontal scroll on small screens -->
            <div class="overflow-x-auto w-full md:w-4/5">
                <table class="min-w-full border border-gray-400 text-sm md:text-base">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="p-2">{{ __('chief.no') }}</th>
                            <th class="p-2">{{ __('chief.title') }}</th>
                            <th class="p-2">{{ __('chief.offender') }}</th>
                            <th class="p-2">{{ __('chief.date') }}</th>
                            <th class="p-2">{{ __('chief.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disputes as $dispute)
                        <tr class="hover:bg-slate-500 text-center cursor-pointer" wire:click="selectDispute({{$dispute->id}})">
                            <td class="p-2">{{ $dispute->id }}</td>
                            <td class="p-2">{{ $dispute->title }}</td>
                            <td class="p-2">{{ $dispute->offender_name }}</td>
                            <td class="p-2">{{ $dispute->created_at->format('Y-m-d H:i') }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($dispute->status === 'Cyoherejwe') bg-yellow-100 text-yellow-800
                                    @elseif($dispute->status === 'Kizasomwa') bg-blue-100 text-blue-800
                                    @elseif($dispute->status === 'cyakemutse') bg-green-100 text-green-800
                                    @endif">
                                    {{ $dispute->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="p-2">
                                {{ $disputes->links() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Chart container -->
            <div class="w-full md:w-1/5 flex justify-center items-start mt-4 md:mt-20">
                <canvas id="disputesChart" class="max-w-full h-auto"></canvas>
            </div>
        </div>

        <!-- Chart Script -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('disputesChart').getContext('2d');
                const disputesChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Byakiriwe', 'Byahawe Abunzi', 'Byakemutse'],
                        datasets: [{
                            data: [
                                {{ $disputeCounts['received'] }},
                                {{ $disputeCounts['assigned'] }},
                                
                                {{ $disputeCounts['resolved'] }}
                            ],
                            backgroundColor: [
                                'rgba(251, 191, 36, 0.7)',
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(139, 92, 246, 0.7)',
                                'rgba(34, 197, 94, 0.7)'
                            ],
                            borderColor: [
                                'rgba(251, 191, 36, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(139, 92, 246, 1)',
                                'rgba(34, 197, 94, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const value = context.raw;
                                        const percentage = ((value / total) * 100).toFixed(2);
                                        return `${context.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Update chart when Livewire updates
                window.livewire.on('disputesUpdated', () => {
                    disputesChart.data.datasets[0].data = [
                        {{ $disputeCounts['received'] }},
                        {{ $disputeCounts['assigned'] }},
                       
                        {{ $disputeCounts['resolved'] }}
                    ];
                    disputesChart.update();
                });
            });
        </script>

        <!-- Modal -->
        <div x-data="{ showModal: false }"
             x-on:open-dispute-modal.window="showModal = true"
             x-on:close-dispute-modal.window="showModal = false">
            <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Ikirego #{{ $selectedDispute->id ?? '' }}</h2>
                    <p class="mb-2">Uregwa: {{ $selectedDispute->offender_name ?? '' }}</p>

                    <div class="mb-3">
                        <label class="block text-sm mb-1">Hitamo Abunzi (abenshi 3)</label>
                        <select wire:model="assignedJustices" multiple class="w-full border p-1">
                            @foreach($justices as $justice)
                                <option value="{{ $justice->id }}">{{ $justice->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm mb-1">Itariki y'Urubanza</label>
                        <input type="datetime-local" wire:model="meetingDate" class="w-full border p-1" />
                    </div>

                    <button wire:loading.attr="disabled" wire:click="assignDispute"  class="bg-green-600 disabled:opacity-30 text-white px-4 py-1 rounded">Emeza</button>
                    <button x-on:click="showModal = false"  class="ml-2 bg-gray-400 px-3 py-1 rounded">Hagarika</button>
                </div>
            </div>
        </div>

        <livewire:aimbaza />
    </x-app-layout>
</div> 