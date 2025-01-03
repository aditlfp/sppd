<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
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
                        <tr class="hover" onclick="window.location='{{ route('main_sppds.store-bottom', $sppd->id) }}'">
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $sppd->maksud_perjalanan}}</td>
                            <td>{{ $sppd->lama_perjalanan . " Hari" }}</td>
                            <td>{{ $sppd->date_time_berangkat }}</td>
                            <td>{{ $sppd->date_time_kembali }}</td>
                            @if ($sppd->verify == "0")
                            <td>
                                <span class="badge badge-warning text-white font-semibold">Waiting</span>
                            </td>
                            @elseif ($sppd->verify == "1")
                            <td>
                                <span class="badge badge-success text-white font-semibold">Diverifikasi</span>
                            </td>
                            @else
                                <td>
                                    <span class="badge badge-error text-white font-semibold">Dalam Perjalanan</span>
                                </td>
                            @endif
                        </tr>
                        @empty
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <p class="py-4 px-5 text-center">Data Tidak Tersedia</p>
                            </div>
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
