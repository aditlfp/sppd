<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Region') }}
        </h2>
    </x-slot>

    <div class="pb-16 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @forelse ($regions as $reg)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5 drop-shadow-sm">
                <div class="p-6 text-gray-900 flex justify-between items-end">
                    <div>
                        <p class="text-lg font-bold">{{ $reg->name }}</p>
                        <p class="badge badge-sm p-2 bg-blue-500 text-white">{{ $reg->nama_daerah }}</p>
                    </div>
                    <div class="flex items-center justify-end gap-x-2">
                        {{-- EDIT --}}
                        <x-btn-edit href="{{ route('regions.edit', $reg->id) }}" />

                        {{-- DELETE --}}
                        <x-btn-delete action="{{ route('regions.destroy', $reg->id) }}" />
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
    <x-btn-create href="{{ route('regions.create') }}"/>
</x-app-layout>
