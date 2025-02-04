<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Eselon') }}
        </h2>
    </x-slot>

    <div class="pb-16 pt-3" x-data="{ searchQuery: '', hasResults: true, jabatan: {{ $jabatans->toJson() ?: '[]' }}, eslons: {{ $eslons->toJson() ?: '[]' }} }"
        @search-updated.window="
            searchQuery = $event.detail; 
            hasResults = eslons.some(eslon => {
                let jabatanNames = (Array.isArray(eslon.jabatan_id) ? eslon.jabatan_id : JSON.parse(eslon.jabatan_id)).map(id => jabatan[id]?.name_jabatan || '');
                return eslon.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                    jabatanNames.some(j => j.toLowerCase().includes(searchQuery.toLowerCase()));
            });">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full flex justify-end p-2 {{ $eslons->count() > 0 ? '' : 'hidden' }}">
                <x-search />
            </div>
            <template x-for="(eslon, i) in eslons" :key="eslon.id">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-5"
                    x-show="searchQuery == '' || eslon.name.toLowerCase().includes(searchQuery.toLowerCase()) || (Array.isArray(eslon.jabatan_id) ? eslon.jabatan_id : JSON.parse(eslon.jabatan_id)).some(id => jabatan[id]?.name_jabatan?.toLowerCase().includes(searchQuery.toLowerCase()))"
                    x-transition:enter="transition-opacity duration-200 ease-in-out"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200 ease-in-out"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="p-6 text-gray-900 flex justify-between items-end">
                        <div>
                            <p x-text="eslon.name" class="text-lg font-bold"></p>
                            <div x-data="{ jabatans: {{ $jabatans->toJson() }} }">
                                <template x-for="jabatanId in eslon.jabatan_id">
                                    <div x-text="jabatans[jabatanId]?.name_jabatan"
                                        class="badge badge-sm p-2 bg-blue-500 text-white"></div>
                                </template>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <!-- EDIT BUTTON -->
                            <x-btn-edit x-bind:href="'/eslons/' + eslon.id + '/edit'" />

                            <!-- DELETE BUTTON -->
                            <x-btn-delete x-bind:action="'/eslons/' + eslon.id" />
                        </div>
                    </div>
                </div>
            </template>
            <!-- No Data Available Message -->
            <div x-show="eslons.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <p class="py-4 px-5 text-center">Data Tidak Tersedia</p>
            </div>
            <!-- No Search Results Message -->
            <div x-cloak x-show="!hasResults && searchQuery !== ''"
                class="min-h-[50svh] inset-0 flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
                No results found
            </div>
            <x-btn-create href="{{ route('eslons.create') }}" />
        </div>
    </div>
</x-app-layout>
