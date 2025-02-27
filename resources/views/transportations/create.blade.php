<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transportation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('transportations.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nama_kendaraan" class="block text-sm font-medium text-gray-700 required">Nama Kendaraan</label>
                            <input type="text" name="nama_kendaraan" id="nama_kendaraan" class="mt-1 block w-full input input-sm input-bordered rounded-sm" placeholder="Nama Kendaraan..." required>
                        </div>

                        <div class="mb-4">
                            <label for="jenis" class="block text-sm font-medium text-gray-700 required">Jenis Kendaraan ( Kantor / Umum )</label>
                            <select name="jenis" id="jenis" class="mt-1 block w-full select select-bordered select-sm text-xs rounded-sm" required>
                                <option disabled selected>-Pilih Jenis Kendaraan-</option>
                                <option value="kendaraan kantor">Kendaraan Kantor</option>
                                <option value="kendaraan umum">Kendaraan Umum</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="anggaran" class="block text-sm font-medium text-gray-700 required">Biaya</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" name="anggaran" id="anggaran" placeholder="Biaya transportasi..." class="mt-1 block w-full input input-sm input-bordered rounded-l-none border-l-0 rounded-sm" required>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ URL::route('transportations.index') }}" class="btn btn-sm btn-warning">BACK</a>
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
