<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form SPPD') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('main_sppds.store') }}" method="POST" class="form-control" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="date_time_arrive" class="block text-sm font-medium text-gray-700 label-text">Date Time Arrive</label>
                            <input type="datetime-local" name="date_time_arrive" id="date_time_arrive" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="arrive_at" class="block text-sm font-medium text-gray-700 label-text">Arrive At</label>
                            <input type="text" name="arrive_at" id="arrive_at" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4" x-data="filePreview()">
                            <label for="foto_arrive" class="block text-sm font-medium text-gray-700 label-text">Foto Arrive</label>
                            <input type="file" name="foto_arrive" id="foto_arrive" class="mt-1 block w-full file-input file-input-bordered rounded-sm input-sm file-input-primary border-gray-300" @change="handleFilePreview">

                            <!-- Preview Section -->
                            <template x-if="imageUrl">
                                <div class="mt-2">
                                    <img :src="imageUrl" alt="Image Preview" class="max-w-full h-auto rounded-sm">
                                </div>
                            </template>
                        </div>
                        <div class="mb-4">
                            <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Continue</label>
                            <input type="checkbox" name="continue" id="continue" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="date_time_destination" class="block text-sm font-medium text-gray-700 label-text">Date Time Destination</label>
                            <input type="datetime-local" name="date_time_destination" id="date_time_destination" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4" x-data="filePreview2()">
                            <label for="foto_destination" class="block text-sm font-medium text-gray-700 label-text">Foto Destination</label>
                            <input type="file" name="foto_destination" id="foto_destination" class="mt-1 block w-full file-input file-input-bordered rounded-sm input-sm file-input-primary border-gray-300" @change="handleFilePreview2">

                            <!-- Preview Section -->
                            <template x-if="imageUrl2">
                                <div class="mt-2">
                                    <img :src="imageUrl2" alt="Image Preview" class="max-w-full h-auto rounded-sm">
                                </div>
                            </template>
                        </div>
                        <div class="mb-4">
                            <label for="nama_diperintah" class="block text-sm font-medium text-gray-700 label-text">Nama Diperintah</label>
                            <input type="text" name="nama_diperintah" id="nama_diperintah" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="date_time" class="block text-sm font-medium text-gray-700 label-text">Date Time</label>
                            <input type="datetime-local" name="date_time" id="date_time" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="verify" class="block text-sm font-medium text-gray-700 label-text">Verify</label>
                            <input type="checkbox" name="verify" id="verify" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="note" class="block text-sm font-medium text-gray-700 label-text">Note</label>
                            <textarea name="note" id="note" placeholder="Note..." class="mt-1 block w-full textarea textarea-bordered textarea-sm"></textarea>
                        </div>
                        <div class="flex items-center justify-end w-full mt-4">
                            <x-primary-button class="w-full">
                                {{ __('Request Verification') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('filePreview', () => ({
                imageUrl: null,
                handleFilePreview(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imageUrl = URL.createObjectURL(file);
                    } else {
                        this.imageUrl = null;
                    }
                },
            }));
            Alpine.data('filePreview2', () => ({
                imageUrl2: null,
                handleFilePreview2(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imageUrl2 = URL.createObjectURL(file);
                    } else {
                        this.imageUrl2 = null;
                    }
                },
            }));
        });
    </script>
</x-app-layout>
