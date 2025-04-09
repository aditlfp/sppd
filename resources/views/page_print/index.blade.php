index
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3" x-data="{ searchQuery: '', hasResults: true, sppds: {{ $mainSppds->toJson() ?: '[]' }} }"
        @search-updated.window="searchQuery = $event.detail; hasResults = sppds.some(sppd => sppd.maksud_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.lama_perjalanan.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.date_time_berangkat.toLowerCase().includes(searchQuery.toLowerCase()) || sppd.date_time_kembali.toLowerCase().includes(searchQuery.toLowerCase()) || (sppd.verify == 0 ? 'waiting' : 'diverifikasi').toLowerCase().includes(searchQuery.toLowerCase()));">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
                <div class="w-full flex justify-end p-2 {{ $mainSppds->count() > 0 ? '' : 'hidden' }}">
                    <x-search />
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Perintah Dari</th>
                                <th>Yang Diperintah</th>
                                <th>Maksud Perjalanan Dinas</th>
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
                                    $latestB = $latestBellow[$sppd->code_sppd]->first();
                                    // dd($latestB);
                                @endphp
                                <tr
                                @if ($latestB->continue == 0)
                                class="hover cursor-pointer"
                                onclick="$('#btnOpen_{{ $index }}').stop(true, true).fadeToggle();"
                                @else
                                class="hover"
                                @endif>
                                    <td>{{ $index + 1  }}</td>
                                    <td>{{ $sppd->auth_official  }}</td>
                                    <td>{{ $sppd->user->nama_lengkap }}</td>
                                    <td>{{ $sppd->maksud_perjalanan }}</td>
                                    <td>{{ $sppd->lama_perjalanan . ' Hari' }}</td>
                                    <td>{{ $sppd->date_time_berangkat }}</td>
                                    <td>{{ $sppd->date_time_kembali }}</td>

                                </tr>


                            @empty
                                <tr class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <td colspan="8">
                                        <p class="py-4 px-5 text-center text-md text-gray-400">Data Tidak Tersedia</p>
                                    </td>
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
    <x-btn-create href="{{ route('main_sppds.create') }}" />

    <script>
           function submitForm(isContinue){
                $('#continueInput').val(isContinue);
                $('#continueForm').submit();
           }
    </script>
</x-app-layout>
