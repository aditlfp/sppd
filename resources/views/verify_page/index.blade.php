<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3" x-data="{ searchQuery: '', hasResults: true, latestBelow: {{ $latestBellow->toJson() ?: '[]' }}, sppds: {{ $mainSppds->toJson() ?: '[]' }} }" @search-updated.window="searchQuery = $event.detail; hasResults = sppds.some(sppd => sppd.maksud_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.lama_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.date_time_berangkat.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.date_time_kembali.toLowerCase().includes(searchQuery.toLowerCase()));">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm overflow-x-auto">
                <div class="w-full flex justify-end p-2 {{ $mainSppds->count() > 0 ? '' : 'hidden' }}">
                    <x-search/>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra overflow-x-auto">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Yang Diperintah</th>
                                <th>Perjalanan Dinas</th>
                                <th>Lamanya Perjalanan</th>
                                <th>Tgl Berangkat</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7">
                                    <div class="max-h-[50svh] min-h-[50svh] overflow-y-auto overflow-x-auto">
                                        <table class="table table-zebra overflow-x-auto">
                                            <template x-for="(sppd, i) in sppds" :key="sppd.id">
                                                <tr 
                                                    x-cloak
                                                    x-show="searchQuery == '' || trans.jenis.toLowerCase().includes(searchQuery.toLowerCase()) || trans.anggaran.toLowerCase().includes(searchQuery.toLowerCase())"
                                                    x-data="{latestB: latestBelow[sppd.code_sppd]}"
                                                    x-transition:enter="transition-opacity duration-200 ease-in-out"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition-opacity duration-200 ease-in-out"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                >
                                                    <td x-text="i+1"></td>
                                                    <td x-text="sppd.user.nama_lengkap"></td>
                                                    <td x-text="sppd.maksud_perjalanan"></td>
                                                    <td x-text="sppd.lama_perjalanan + ' Hari'"></td>
                                                    <td x-text="sppd.date_time_berangkat"></td>
                                                    <td x-text="sppd.date_time_kembali"></td>
                                                    <template x-if="sppd.verify == '0' || sppd.verify == null">
                                                        <td>
                                                            <span class="badge badge-warning text-white font-semibold">Waiting</span>
                                                        </td>
                                                    </template>
                                                    <template x-if="sppd.verify == '1'">
                                                        <td>
                                                            <template x-if="latestB">
                                                                <template x-if="latestB.continue == 0" x-text="'Diverifikasi & Selesai'" class="badge badge-success text-white font-semibold"></template>
                                                                <template x-if="latestB.continue == 1" x-text="'Diverifikasi & Dalam Perjalanan'" class="badge badge-success text-white font-semibold"></template>
                                                            </template>
                                                        </td>
                                                    </template>
                                                    <template x-if="sppd.verify == '2'">
                                                        <td>
                                                            <template x-if="latestB">
                                                                <template x-if="latestB.continue == 0" x-text="'Diverifikasi & Selesai'" class="badge badge-success text-white font-semibold"></template>
                                                                <template x-if="latestB.continue == 1" x-text="'Diverifikasi & Dalam Perjalanan'" class="badge badge-success text-white font-semibold"></template>
                                                            </template>
                                                        </td>
                                                    </template>
                                                    {{-- VERIFY --}}
                                                        <td>
                                                            <a :href="'/verify/' + sppd.id" x-text="'See Details'" class="btn btn-sm btn-primary rounded-sm"></a>
                                                        </td>
                                                    {{-- VERIFY --}}
                                                </tr>
                                            </template>
                                            <tr x-show="sppds.length === 0">
                                                <td colspan="7" class="text-center">No data available</td>
                                            </tr>
                                        </table>
                                        <!-- No Results Message -->
                                        <div x-cloak x-show="!hasResults && searchQuery !== ''"
                                            class="min-h-[50svh] inset-0 flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
                                            No results found
                                        </div>
                                    </div>
                                </td>
                            </tr>
                      </tbody>
                    </table>
                  </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('main_sppds.create') }}"/>
</x-app-layout>
