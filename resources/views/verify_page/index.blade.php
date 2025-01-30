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
                                    @php
                                        $latestB = $latestBellow[$sppd->code_sppd] ?? null;
                                    @endphp

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
                            @else
                                <td>
                                    <span class="badge badge-error text-white font-semibold">Dalam Perjalanan</span>
                                </td>
                            @endif
                            {{-- VERIFY --}}
                                <td>
                                    @if ($sppd->verify == "0" || $sppd->verify == null)
                                        <form action="{{ route('verifyUpdate', $sppd->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="name_verify" value="verify_departure" hidden>
                                            <button type="submit" class="btn sm:btn-sm btn-md btn-primary rounded-sm"><svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM17.4571 9.45711L11 15.9142L6.79289 11.7071L8.20711 10.2929L11 13.0858L16.0429 8.04289L17.4571 9.45711Z"></path></svg>Verifikasi</button>
                                        </form>

                                    @endif
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
