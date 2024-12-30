<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Eselon') }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($eslons as $eslon)

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-bold">{{ $eslon->name }}</p>
                    @foreach ($eslon->jabatan_id as $item)
                        @php
                            $jabatan = App\Models\Jabatan::find($item);
                        @endphp
                        <div class="badge bg-blue-500 text-white">{{ $jabatan->name_jabatan}}</div>
                    @endforeach

                    <div class="flex items
                    -center justify-end gap-x-2">

                       {{-- EDIT --}}
                        <x-btn-edit href="{{ route('eslons.edit', $eslon->id) }}"/>

                        {{-- DELETE --}}
                        <x-btn-delete action="{{ route('eslons.destroy', $eslon->id) }}"/>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="py-4 px-5 text-center">Data Tidak Tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
