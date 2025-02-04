<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3" x-data="{ searchQuery: '', hasResults: true, sppds: {{ $mainSppds->toJson() ?: '[]' }} }" @search-updated.window="searchQuery = $event.detail; hasResults = sppds.some(sppd => sppd.maksud_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.lama_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || || sppd.date_time_berangkat.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.date_time_kembali.toLowerCase().includes(searchQuery.toLowerCase()) || (sppd.verify == 0 ? 'waiting' : 'diverifikasi').toLowerCase().includes(searchQuery.toLowerCase()));">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
                <div class="w-full flex justify-end p-2 {{ $mainSppds->count() > 0 ? '' : 'hidden' }}">
                    <x-search/>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Perjalanan Dinas</th>
                                <th>Lamanya Perjalanan</th>
                                <th>Tgl Berangkat</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- row 1 -->
                        @forelse ($mainSppds as $index => $sppd)
                        @php
                            $latestB = $latestBellow[$sppd->code_sppd] ?? null;
                        @endphp
                        <tr class="hover" @if ($sppd->verify == "1" || $sppd->verify == "2")
                        onclick="window.location='{{ route('main_sppds.store-bottom', $sppd->id) }}'"
                        @else
                        onclick="$.notify('SPPD Belum Diverifikasi!', {
                            autoHideDelay: 2000,
                            className: 'error',
                        })"
                        @endif >
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $sppd->maksud_perjalanan}}</td>
                            <td>{{ $sppd->lama_perjalanan . " Hari" }}</td>
                            <td>{{ $sppd->date_time_berangkat }}</td>
                            <td>{{ $sppd->date_time_kembali }}</td>
                            @if ($sppd->verify == "0" || $sppd->verify == null)
                            <td>
                                <span class="badge badge-warning text-white font-semibold">Waiting</span>
                            </td>
                            @elseif ($sppd->verify == "1")
                                <td>
                                    @if ($latestB)
                                        @if ($latestB->continue == 0)
                                            <span class="badge badge-success text-white font-semibold">Diverifikasi & Selesai</span>
                                        @elseif ($latestB->continue == 1)
                                            <span class="badge badge-success text-white font-semibold">Diverifikasi & Dalam Perjalanan</span>
                                        @endif
                                    @else
                                        <span class="badge badge-error text-white font-semibold">Dalam Perjalanan</span>
                                    @endif
                                </td>
                            @elseif($sppd->verify == "2")
                                <td>
                                    @if ($latestB)
                                        @if ($latestB->continue == 0)
                                            <span class="badge badge-success text-white font-semibold">Diverifikasi & Selesai</span>
                                        @elseif ($latestB->continue == 1)
                                            <span class="badge badge-error text-white font-semibold">Diverifikasi & Dalam Perjalanan</span>
                                        @endif
                                    @else
                                        <span class="badge badge-error text-white font-semibold">Dalam Perjalanan</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @empty
                            <tr class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                               <td colspan="6"><p class="py-4 px-5 text-center text-md text-gray-400">Data Tidak Tersedia</p></td>
                            </tr>
                        @endforelse
                      </tbody>
                    </table>
                    <div class="pagination">
                        {{ $mainSppds->links() }}
                    </div>
                  </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('main_sppds.create') }}"/>
</x-app-layout>
