<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="pb-12 pt-3" 
    x-data="{
        searchQuery: '',
        hasResults: true,
        latestBelow: {{ $latestBellow->toJson() ?: '[]' }},
        sppds: {{ $sppds->toJson() ?: '[]' }},
        get filteredSppds() {
            let query = this.searchQuery.toLowerCase();
            return this.sppds.filter(sppd => 
                [
                    sppd.user.nama_lengkap,
                    sppd.maksud_perjalanan,
                    sppd.lama_perjalanan,
                    sppd.date_time_berangkat,
                    sppd.date_time_kembali,
                    this.getStatusText(sppd)
                ].some(field => field.toLowerCase().includes(query))
            );
        },
        getStatusText(sppd) {
            if (sppd.verify == '0' || sppd.verify == null) return 'Wait';
            if (sppd.verify == '1' || sppd.verify == '2') {
                return this.latestBelow[sppd.code_sppd] && this.latestBelow[sppd.code_sppd].continue == 0 
                    ? 'Verif' + (window.innerWidth <= 768 ? '' : ' & ' + 'Done') 
                    : 'Verif' + (window.innerWidth <= 768 ? '' : ' & ' + 'Otw');
            };
            if (sppd.verify == '3') return 'Ditolak';
            return '';
        }
    }"
    @search-updated.window="searchQuery = $event.detail; hasResults = filteredSppds.length > 0">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-2 items-center">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm mx-1 md:w-2/5 p-2 flex flex-col justify-between">
                <p class="text-center font-semibold p-2">Chart SPPD Bulan Ini</p>
                <div class="m-3">
                    <canvas id="dailyChart"></canvas>
                </div>
                <div class="h-[15%]"></div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-sm mx-1 md:w-3/5 p-2">
                <p class="text-center font-semibold p-2">SPPD Bulan Ini</p>
                <table class="table table-sm table-zebra">
                    <thead>
                        <tr>
                            <th class="w-[5.5%] text-center text-xs">#</th>
                            <th class="w-[18%] text-center text-xs">Pelaksana</th>
                            <th class="w-[18%] text-center text-xs">PerDin</th>
                            <th x-show="window.innerWidth >= 768" class="w-[6%] text-center text-xs">Lama <br/> (Hari)</th>
                            <th class="w-[20%] text-center text-xs">OTW</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="padding: 0;">
                                <!-- Wrap the tbody content in a scrollable div -->
                                <div class="max-h-[50svh] min-h-[50svh] overflow-y-auto">
                                    <table class="table table-sm table-zebra">
                                        <template x-for="(sppd, i) in sppds" :key="sppd.id">
                                            <tr 
                                                x-cloak
                                                x-data="{ latestB: latestBelow[sppd.code_sppd] }"
                                                x-transition:enter="transition-opacity duration-300 ease-in-out"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300 ease-in-out"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                            >
                                                <td x-text="i + 1 + '.'" class="w-[5.5%] text-xs"></td>
                                                <td x-text="sppd.user.nama_lengkap" class="w-[18%] text-xs"></td>
                                                <td x-text="sppd.maksud_perjalanan" class="w-[18%] text-xs"></td>
                                                <td x-text="sppd.lama_perjalanan" x-show="window.innerWidth >= 768" class="w-[9%] text-xs text-center"></td>
                                                <td x-text="sppd.date_time_berangkat" class="w-[26%] md:w-[20%] text-xs text-center"></td>
                                                <td class="text-center text-xs">
                                                    <span :class="{
                                                        'badge badge-warning text-white font-semibold badge-sm': sppd.verify == '0' || sppd.verify == null,
                                                        'badge badge-success text-white font-semibold badge-sm': sppd.verify == '1' || sppd.verify == '2',
                                                        'badge badge-error text-white font-semibold badge-sm': sppd.verify == '3'
                                                    }" x-text="getStatusText(sppd)"></span>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="sppds.length === 0">
                                            <td colspan="7" class="text-center">No data available</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="hidden md:block">
            <x-footer/>
        </div>
    </div>
    <script>
        var auth = @json(Auth::user()->role_id);
        var labelsDaily = @json($dates); // X-axis labels (dates in current month)
        var dataViews = @json($dataViews); // Viewable data per day
        var dataSppd = @json($dataSppd); // SPPD count per day
    
        $(document).ready(function () {
            var ctx = document.getElementById('dailyChart').getContext('2d');
    
            var datasets = [
                {
                    label: 'SPPD Entries (Daily)',
                    data: dataSppd,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }
            ];
    
            // Conditionally add the "Total Viewable" dataset if auth == 2
            auth == 2 && datasets.push({
                label: 'Total Viewable (Daily)',
                data: dataViews,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            });
    
            var chartData = {
                labels: labelsDaily,
                datasets: datasets
            };
    
            var chartOptions = {
                responsive: true,
                scales: {
                    x: {
                        title: { display: true, text: 'Date (Current Month)' }
                    },
                    y: {
                        title: { display: true, text: 'Count' },
                        beginAtZero: true,
                        suggestedMin: 0
                    }
                }
            };
    
            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: chartOptions
            });
        });
    </script>
    
</x-app-layout>
