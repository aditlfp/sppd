<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Region') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('regions.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 required">Wilayah</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full input input-bordered input-sm" required placeholder="Masukkan jenis wilayah (Wilayah I, Wilayah II, dst)">
                        </div>
                        <div class="mb-4">
                            <label for="nama_daerah" class="block text-sm font-medium text-gray-700 required">Nama Wilayah</label>
                            <input type="text" name="nama_daerah" id="nama_daerah" class="mt-1 block w-full input input-bordered input-sm" required placeholder="Masukkan nama wilayah yang dijangkau (Madiun dan Surabaya, dst)">
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
