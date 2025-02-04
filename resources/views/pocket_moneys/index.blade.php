<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Uang Saku') }}
        </h2>
    </x-slot>

    <div class="pb-16 pt-3" x-data="{ searchQuery: '', hasResults: true, uangSaku: {{ $pocketMoneys->toJson() ?: '[]' }} }" @search-updated.window="searchQuery = $event.detail; hasResults = uangSaku.some(saku => saku.eslon.name.toLowerCase().includes(searchQuery.toLowerCase()) || saku.region.name.toLowerCase().includes(searchQuery.toLowerCase()) || saku.anggaran.toString().includes(searchQuery));">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
                <div class="w-full flex justify-end p-2 {{ $pocketMoneys->count() > 0 ? '' : 'hidden' }}">
                    <x-search/>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Eselon</th>
                                <th>Region</th>
                                <th>Anggaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- body --}}
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <!-- Wrap the tbody content in a scrollable div -->
                                    <div class="max-h-[50svh] min-h-[50svh] overflow-y-auto">
                                        <table class="table table-sm table-zebra">
                                            <template x-for="(saku, i) in uangSaku" :key="saku.id">
                                                <tr 
                                                    x-cloak
                                                    x-show="searchQuery == '' || saku.eslon.name.toLowerCase().includes(searchQuery.toLowerCase()) || saku.region.name.toLowerCase().includes(searchQuery.toLowerCase()) || saku.anggaran.toString().includes(searchQuery)"
                                                    x-transition:enter="transition-opacity duration-200 ease-in-out"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition-opacity duration-200 ease-in-out"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
    >
                                                    <td x-text="i+1"></td>
                                                    <td x-text="saku.eslon.name" class="text-sm"></td>
                                                    <td x-text="saku.region.name" class="text-sm"></td>
                                                    <td x-text="toRupiah(saku.anggaran)"></td>
                                                    <td class="flex gap-2 justify-center">
                                                        {{-- EDIT --}}
                                                        <x-btn-edit x-bind:href="'/pocket_moneys/' + saku.id + '/edit'" />
                                                        {{-- DELETE --}}
                                                        <x-btn-delete x-bind:action="'/pocket_moneys/' + saku.id" />
                                                    </td>
                                                </tr>
                                            </template>
                                                <tr x-show="uangSaku.length === 0">
                                                    <td colspan="5" class="text-center">No data available</td>
                                                </tr>
                                        </table>
                                        <!-- No Results Message -->
                                        <div x-cloak x-show="!hasResults && searchQuery !== ''"
                                            class="min-h-[50svh] inset-0 flex items-center justify-center bg-white text-gray-500 text-lg font-semibold">
                                            No results found
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('pocket_moneys.create') }}"/>
    <style>
        td {
            font-size: 0.875rem;
            line-height: var(--text-sm--line-height);
        }
    </style>
    <script>
        function toRupiah(value) {
            return 'Rp ' + Number(value).toLocaleString('id-ID');
        }
    </script>
</x-app-layout>
