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
                        <tr class="hover" @if ($sppd->verify == "1" || $sppd->verify == "2")
                        onclick="window.location='{{ route('main_sppds.store-bottom', $sppd->id) }}'"
                        @else
                        onclick="$.notify('SPPD Belum Diverifikasi!', {
                            autoHideDelay: 2000,
                            className: 'error',
                        })"
                        @endif >
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sppd->user->nama_lengkap }}</td>
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
                                <span class="badge badge-success text-white font-semibold">Diverifikasi</span>
                            </td>
                            @else
                                <td>
                                    <span class="badge badge-error text-white font-semibold">Dalam Perjalanan</span>
                                </td>
                            @endif
                            {{-- VERIFY --}}
                                {{-- <td>
                                    @if ($sppd->verify == "0" || $sppd->verify == null)
                                        <form action="{{ route('verify.update', $sppd->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="name_verify" value="verify_departure" hidden>
                                            <button type="submit" class="btn btn-sm btn-primary">Verifikasi</button>
                                        </form>
                                    @elseif ($sppd->verify == "2")
                                        <form action="{{ route('verify.update', $sppd->id)}}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="name_verify" value="verify_arrive" hidden>
                                            <button type="submit" class="btn btn-sm btn-secondary">Verifikasi</button>
                                        </form>
                                    @endif
                                </td> --}}
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
