<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data SPPD') }} {{ Carbon\Carbon::now()->year }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($mainSppds as $sppd)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            </div>
            @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="py-4 px-5 text-center">Data Tidak Tersedia</p>
            </div>

            @endforelse
        </div>
    </div>
    <x-btn-create href="{{ route('main_sppds.create') }}"/>
</x-app-layout>
