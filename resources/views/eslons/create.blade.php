<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Eslon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('eslons.store') }}" method="POST" x-data="multiSelect()">
                        @csrf
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Multi-Select Dropdown -->
                        <div class="mb-4">
                            <label for="jabatan_id" class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <div @click="toggle" class="select select-bordered h-auto w-full cursor-pointer mt-1">
                                <div class="flex justify-between items-center w-auto">
                                    <span x-text="selectedOptions.length ? selectedOptions.map(id => getOptionName(id)).join(', ') : 'Select options'"></span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div x-show="isOpen" @click.away="close" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
                                <ul class="max-h-60 overflow-auto">
                                    <template x-for="option in options" :key="option.id">
                                        <li @click="select(option.id)" class="cursor-pointer select-none relative py-2 pl-10 pr-4 hover:bg-gray-100">
                                            <span x-text="option.name" class="block truncate"></span>
                                            <span x-show="selectedOptions.includes(option.id)" class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Selected Options -->
                        <template x-for="id in selectedOptions" :key="id">
                            <input type="hidden" name="jabatan_id[]" :value="id">
                        </template>

                        <!-- Debugging: Display selectedOptions -->
                        <div x-text="JSON.stringify(selectedOptions)" class="hidden"></div>

                        <!-- Submit Button -->
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

    <!-- Alpine.js Multi-Select Script -->
    <script>
        function multiSelect() {
            return {
                isOpen: false,
                options: @json($jabatan->map(fn($j) => ['id' => $j->id, 'name' => $j->name_jabatan])),
                selectedOptions: [],
                toggle() {
                    this.isOpen = !this.isOpen;
                },
                close() {
                    this.isOpen = false;
                },
                select(id) {
                    const index = this.selectedOptions.indexOf(id);
                    if (index === -1) {
                        this.selectedOptions.push(id);
                    } else {
                        this.selectedOptions.splice(index, 1);
                    }
                },
                getOptionName(id) {
                    const option = this.options.find(opt => opt.id === id);
                    return option ? option.name : '';
                }
            };
        }
    </script>
</x-app-layout>
