<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm overflow-x-auto">
                <div class="overflow-x-auto">
                    <table class="table table-zebra overflow-x-auto">
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
                        <tr>
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
                            {{-- VERIFY --}}
                                <td>
                                    <a href="{{ route('verify_page.index', $sppd->id)}}" class="btn btn-sm btn-primary rounded-sm">See Details</a>
                                </td>
                            {{-- VERIFY --}}
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
