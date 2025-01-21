<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Uang Saku') }}
        </h2>
    </x-slot>

    <div class="pb-16 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white mx-2 sm:mx-0 overflow-hidden shadow-sm rounded-sm">
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
                            @forelse ($pocketMoneys as $index => $money)
                            <tr>
                                <td>{{ $index+1 }}.</td>
                                <td>{{ $money->eslon->name }}</td>
                                <td>{{ $money->region->name }}</td>
                                <td>{{ toRupiah($money->anggaran) }}</td>
                                <td class="flex gap-2">
                                    {{-- EDIT --}}
                                    <x-btn-edit href="{{ route('pocket_moneys.edit', $money->id) }}" />
                                    {{-- DELETE --}}
                                    <x-btn-delete action="{{ route('pocket_moneys.destroy', $money->id) }}" />
                                </td>
                            </tr>
                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('pocket_moneys.create') }}"/>
</x-app-layout>
