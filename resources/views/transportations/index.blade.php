<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Transportasi') }}
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
                                <th>Jenis Kendaraan</th>
                                <th>Anggaran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- body --}}
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    <!-- Wrap the tbody content in a scrollable div -->
                                    <div class="max-h-[50svh] overflow-y-auto">
                                        <table class="table table-sm table-zebra">
                                            @forelse ($transportations as $index => $trans)
                                            <tr>
                                                <td>{{ $index+1 }}.</td>
                                                <td>{{ $trans->jenis }}</td>
                                                <td>{{ toRupiah($trans->anggaran) }}</td>
                                                <td class="flex gap-2">
                                                    {{-- EDIT --}}
                                                    <x-btn-edit href="{{ route('transportations.edit', $trans->id) }}" />
                                                    {{-- DELETE --}}
                                                    <x-btn-delete action="{{ route('transportations.destroy', $trans->id) }}" />
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No data available</td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-1">
                    {{ $transportations->links() }}
                </div>
            </div>
        </div>
    </div>
    <x-btn-create href="{{ route('transportations.create') }}"/>
</x-app-layout>
