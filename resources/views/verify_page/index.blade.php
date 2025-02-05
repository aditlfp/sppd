<!-- filepath: /d:/PROJECT/LARAVEL/sppd/resources/views/verify_page/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3" 
        x-data="{
            searchQuery: '',
            hasResults: true,
            latestBelow: {{ $latestBellow->toJson() ?: '[]' }},
            sppds: {{ $mainSppds->toJson() ?: '[]' }},
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
                    return this.latestBelow[sppd.code_sppd] && this.latestBelow[sppd.code_sppd].continue == 0 
                        ? 'Diverifikasi' + ' & ' + 'Selesai' 
                        : 'Diverifikasi' + ' & ' + 'Dalam Perjalanan';
                }
                return '';
            }
        }"
        @search-updated.window="searchQuery = $event.detail; hasResults = filteredSppds.length > 0">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
                <div class="w-full flex justify-end p-2" x-show="sppds.length > 0">
                    <x-search/>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra table-sm">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th class="w-[4%]">#</th>
                                <th class="w-[16%]">Yang Diperintah</th>
                                <th class="w-[14%]">Perjalanan Dinas</th>
                                <th class="w-[14%]">Lamanya Perjalanan</th>
                                <th>Tgl Berangkat - Kembali</th>
                                <th>Status</th>
                                <th class="w-[12%]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(sppd, i) in sppds" :key="sppd.id">
                                <tr 
                                    x-cloak
                                    x-data="{ latestB: latestBelow[sppd.code_sppd] }"
                                    x-show="searchQuery == '' || sppd.user.nama_lengkap.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                            sppd.maksud_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) ||
                                            sppd.lama_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                            sppd.date_time_berangkat.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                            sppd.date_time_kembali.toLowerCase().includes(searchQuery.toLowerCase()) || 
                                            getStatusText(sppd).toLowerCase().includes(searchQuery.toLowerCase())"
                                    x-transition:enter="transition-opacity duration-300 ease-in-out"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition-opacity duration-300 ease-in-out"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                >
                                    <td x-text="i + 1" class="w-[4%]"></td>
                                    <td x-text="sppd.user.nama_lengkap" class="w-[16%]"></td>
                                    <td x-text="sppd.maksud_perjalanan" class="w-[14%]"></td>
                                    <td x-text="sppd.lama_perjalanan + ' Hari'" class="w-[14%]"></td>
                                    <td x-text="sppd.date_time_berangkat + ' - ' + sppd.date_time_kembali"></td>
                                    <td>
                                        <span :class="{
                                            'badge badge-warning text-white font-semibold': sppd.verify == '0' || sppd.verify == null,
                                            'badge badge-success text-white font-semibold': sppd.verify == '1' || sppd.verify == '2'
                                        }" x-text="getStatusText(sppd)"></span>
                                    </td>
                                    <td class="w-[12%]">
                                        <a :href="'/verify/' + sppd.id" class="btn btn-sm btn-primary rounded-sm">See Details</a>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="sppds.length === 0">
                                <td colspan="7" class="text-center">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- No Results Message -->
                    <div x-cloak x-show="!hasResults && searchQuery !== ''"
                        class="min-h-[50svh] inset-0 flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
                        No results found
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('main_sppds.create') }}"/>
</x-app-layout>