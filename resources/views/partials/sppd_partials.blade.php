<div class="pb-12 pt-3"
    x-cloak
    x-data="{
        searchQuery: '',
        hasResults: true,
        latestB: '',
        latestBelow: {{ json_encode($latestBellow) }},
        sppds: {{ json_encode($sppds->items()) }},
        pagination: {
            current_page: {{ $sppds->currentPage() }},
            last_page: {{ $sppds->lastPage() }},
            per_page: {{ $sppds->perPage() }},
            total: {{ $sppds->total() }}
        },
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
            if (sppd.verify == '0' || sppd.verify == null) return 'Waiting';
            if (sppd.verify == '1' || sppd.verify == '2') {
                return this.latestBelow[sppd.code_sppd] && this.latestBelow[sppd.code_sppd][0].continue == 0
                    ? 'Diverifikasi' + (window.innerWidth <= 768 ? '' : ' & Selesai')
                    : 'Perjalanan' + (window.innerWidth <= 768 ? '' : '');
            };
            if (sppd.verify == '3') return 'Ditolak';
            return '';
        },
        fetchPage(page) {
            fetch(`/dashboard?page=${page}`)
                .then(res => res.json())
                .then(data => {
                    this.sppds = data.sppds.data; // Update data sppds
                    this.pagination = {
                        current_page: data.sppds.current_page,
                        last_page: data.sppds.last_page,
                        per_page: data.sppds.per_page,
                        total: data.sppds.total
                    };
                });
        }
    }"
    @search-updated.window="searchQuery = $event.detail; hasResults = filteredSppds.length > 0"
>
    <div class="mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-2 items-center">
        @if (Auth::user()->role_id == 2)
        <div class="bg-white overflow-hidden shadow-sm rounded-sm mx-1 md:w-2/5 p-2 flex flex-col justify-between">
            <p class="text-center font-semibold p-2">Chart SPPD Bulan Ini</p>
            <div class="m-3">
                <canvas id="dailyChart"></canvas>
            </div>
            <div class="h-[15%]"></div>
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-sm mx-1 {{ Auth::user()->role_id == 1 ? 'md:w-full' : 'md:w-3/5 ' }} p-2 w-screen sm:w-auto">
            <p class="text-center font-semibold p-2">SPPD Bulan Ini</p>
            <div class="overflow-x-auto">
                <table class="table table-sm table-zebra">
                    <thead>
                        <tr>
                            <th class="text-center text-xs">#</th>
                            <th class="text-center text-xs">Pelaksana</th>
                            <th class="text-center text-xs">Maksud Perjalan</th>
                            <th x-show="window.innerWidth >= 768" class="w-[6%] text-center text-xs">Lama (Hari)</th>
                            <th class="text-center text-xs">OTW</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(sppd, i) in filteredSppds" :key="sppd.id">
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
                                <td x-text="sppd.user.nama_lengkap" class="w-auto text-xs"></td>
                                <td x-text="sppd.maksud_perjalanan" class="w-auto text-xs"></td>
                                <td x-text="sppd.lama_perjalanan" x-show="window.innerWidth >= 768" class="w-[9%] text-xs text-center"></td>
                                <td x-text="sppd.date_time_berangkat" class="min-w-[100px] md:min-w-[100px] text-xs text-center"></td>
                                <td class="text-center text-xs">
                                    <span :class="{
                                        'badge badge-warning text-white font-semibold badge-sm': sppd.verify == '0' || sppd.verify == null,
                                        'badge badge-success text-white font-semibold badge-sm': sppd.verify == '1' || sppd.verify == '2',
                                        'badge badge-error text-white font-semibold badge-sm': sppd.verify == '3'
                                    }" x-text="getStatusText(sppd)"></span>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredSppds.length === 0">
                            <td colspan="7" class="text-center">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination mt-2">
                {{ $sppds->links() }}
            </div>
        </div>
    </div>
</div>
