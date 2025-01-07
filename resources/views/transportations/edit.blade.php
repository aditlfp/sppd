<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Transportation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('transportations.update', $transportation->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700 required">Jenis Kendaraan ( Kantor / Umum )</label>
                            <select name="jenis" id="jenis" class="mt-1 block w-full select select-bordered select-sm text-xs" required>
                                <option disabled selected>-Pilih Jenis Kendaraan-</option>
                                <option {{ $transportation->jenis == 'kendaraan kantor' ? 'selected' : '' }} value="kendaraan kantor">Kendaraan Kantor</option>
                                <option {{ $transportation->jenis == 'kendaraan umum' ? 'selected' : '' }} value="kendaraan umum">Kendaraan Umum</option>
                            </select>
                        </div>
                        {{-- Temporarily Hidden  --}}
                        <div class="mb-4 hidden">
                            <label for="anggaran_driver" class="block text-sm font-medium text-gray-700">Anggaran Driver</label>
                            <input type="text" name="anggaran_driver" id="anggaran_driver" class="mt-1 block w-full">
                        </div>
                        {{-- Temporarily Hidden  --}}
                        <div class="mb-4">
                            <label for="anggaran" class="block text-sm font-medium text-gray-700 required">Biaya</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm" value="Rp.">
                                <input type="text" name="anggaran" id="anggaran" value="{{ $transportation?->anggaran }}" placeholder="Biaya transportasi..." class="mt-1 block w-full input input-sm input-bordered rounded-l-none" required>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
