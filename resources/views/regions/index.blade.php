<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Region') }}
        </h2>
    </x-slot>

    <div class="pb-16 pt-3" x-data="{ searchQuery: '', hasResults: true, region: {{ $regions->toJson() ?: '[]' }} }" @search-updated.window="searchQuery = $event.detail; hasResults = region.some(reg => reg.name.toLowerCase().includes(searchQuery.toLowerCase()) || reg.nama_daerah.toLowerCase().includes(searchQuery.toLowerCase()));">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end p-2 {{ $regions->count() > 0 ? '' : 'hidden' }}">
                <x-search/>
            </div>
            <template x-for="reg in region" :key="reg.id">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5 drop-shadow-sm"
                     x-show="searchQuery == '' || reg.name.toLowerCase().includes(searchQuery.toLowerCase()) || reg.nama_daerah.toLowerCase().includes(searchQuery.toLowerCase())"
                     x-transition:enter="transition-opacity duration-200 ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-200 ease-in-out"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">

                    <div class="p-6 text-gray-900 flex justify-between items-end">
                        <div>
                            <p class="text-lg font-bold" x-text="reg.name"></p>
                            <p class="badge badge-sm p-2 bg-blue-500 text-white" x-text="reg.nama_daerah"></p>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <!-- EDIT BUTTON -->
                            <x-btn-edit x-bind:href="'/regions/' + reg.id + '/edit'" />

                            <!-- DELETE BUTTON -->
                            <x-btn-delete x-bind:action="'/regions/' + reg.id" />
                        </div>
                    </div>
                </div>
            </template>
            <!-- No Data Available Message -->
            <div x-show="region.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="py-4 px-5 text-center">Data Tidak Tersedia</p>
            </div>
            <!-- No Search Results Message -->
            <div x-cloak x-show="!hasResults && searchQuery !== ''"
                 class="min-h-[50svh] inset-0 flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
                No results found
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('regions.create') }}"/>
</x-app-layout>
