<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Pocket Money') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('pocket_moneys.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="anggaran" class="block text-sm font-medium text-gray-700 required">Anggaran Uang Saku</label>
                            <input type="text" name="anggaran" id="anggaran" class="mt-1 block w-full input input-sm input-bordered rounded-sm" required placeholder="Rp. 1.000.000">
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <div class="mb-4 w-full">
                                <label for="eslon_id" class="block text-sm font-medium text-gray-700 required">Eselon ID</label>
                                <select name="eslon_id" id="eslon_id" class="w-full select select-sm select-bordered text-xs rounded-sm">
                                    <option disabled selected>-Pilih Eselons-</option>
                                    @foreach ($eslons as $eslon)
                                        <option value="{{ $eslon->id }}">{{ $eslon->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p>-</p>
                            <div class="mb-4 w-full">
                                <label for="region_id" class="block text-sm font-medium text-gray-700 required">Region ID</label>
                                <select name="region_id" id="region_id" class="w-full select select-sm select-bordered text-xs rounded-sm">
                                    <option disabled selected>-Pilih Wilayah-</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name . " - " . $region->nama_daerah }}</option>
                                    @endforeach
                                </select>
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
