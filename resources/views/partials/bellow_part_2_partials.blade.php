@forelse ($bellow as $index => $datas)
    @if ($datas->date_time_arrive != null)
        <h2 class="mt-5 font-semibold text-md sm:text-xl text-gray-800 leading-tight bg-gray-200 rounded-sm p-4">
            Bukti {{ $datas->continue == 1 ? "Kedatangan" : "Pulang Ke Kantor/Rumah" }}
        </h2>

        <div class="py-3">
            <div @class([
                '',
                'max-w-7xl mx-auto sm:px-6 lg:px-8' => !Route::is('verify_page.*')
                ])>
                <div
                @class([
                    '',
                    'bg-white overflow-hidden shadow-sm sm:rounded-lg' => !Route::is('verify_page.*')
                    ])>
                @if ($errors->any())
                    <div class="m-4 mx-8 flex flex-col gap-y-2">
                            @foreach ($errors->all() as $error)
                                <span class="text-red-500 text-sm italic">{{ $error }} !</span>
                            @endforeach
                    </div>
                @endif
                    <div
                    @class([
                        'text-gray-900',
                        'p-6 text-gray-900' => !Route::is('verify_page.*')
                        ])>
                        <form action="{{ route('main_sppds.update', $mainSppd->id) }}" method="POST" class="form-control" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="border border-gray-200 p-4">
                                <div class="flex w-full gap-x-2 sm:gap-x-4">
                                    <div class="mb-4 w-full">
                                        <label for="arrive_at" class="block text-sm font-medium required text-gray-700 label-text">Tiba di</label>
                                        <input type="text" name="arrive_at" id="arrive_at" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" placeholder="Lokasi sekarang..." required value="{{ $datas->arrive_at }}">
                                    </div>
                                    <div class="mb-4 w-full">
                                        <label for="date_time_arrive" class="block text-sm font-medium required text-gray-700 label-text">Pada Tanggal</label>
                                        <input type="text" readonly name="date_time_arrive" id="localDateTime" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" value="{{ $datas->date_time_arrive }}">
                                    </div>
                                </div>
                                <div class="mb-4" x-data="filePreview('{{ URL::asset($datas->foto_arrive ? 'storage/images/' . $datas->foto_arrive : 'img/no-image.jpg') }}')">
                                    <label for="foto_arrive" class="block text-sm font-medium text-gray-700 label-text required">Foto Kedatangan</label>

                                    <!-- Preview Section -->
                                    <template x-if="imageUrl">
                                        <div class="my-2 flex items-center justify-center">
                                            <img :src="imageUrl" alt="Image Preview" class="max-w-[275px] h-auto rounded-sm">
                                        </div>
                                    </template>

                                    <input type="file" required name="foto_arrive" id="foto_arrive" accept="image/*" class="hidden mt-1 w-full file-input file-input-bordered rounded-sm input-sm file-input-primary border-gray-300" @change="handleFilePreview">
                                </div>

                                <div id="map1_{{ $index }}" class="map-container"></div>
                                <input type="text" name="maps_tiba_{{ $index }}" id="maps_tiba_{{ $index }}" hidden readonly>
                            </div>
                            <div class="border border-gray-200 p-4 mt-4">
                                <div class="mb-4 flex flex-col gap-y-2">
                                    <label for="continue" class="block text-sm font-medium text-gray-700 label-text required">Lanjut/Pulang</label>
                                    <div class="flex items-center gap-x-2">
                                        <input type="radio" {{ $datas->continue == 1 ? 'checked' : 'disabled' }} name="continue" value="1" class="mt-2 radio bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600">
                                        <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Melanjutkan Perjalanan</label>
                                    </div>
                                    <div class="flex items-center gap-x-2">
                                        <input type="radio" {{ $datas->continue == 0 ? 'checked' : 'disabled' }} name="continue" value="0" class="mt-2 radio bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600">
                                        <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Pulang Ke Kantor</label>
                                    </div>
                                </div>


                            <div class="mb-4">
                                <label for="note" class="block text-sm font-medium text-gray-700 label-text">Note</label>
                                <textarea name="note" id="note" placeholder="Note..." class="mt-1 block w-full textarea textarea-bordered textarea-sm rounded-sm">{{ $datas->note }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@empty
    <div class="bg-gray-200 w-full p-4 rounded-sm italic text-center text-xs">
        <span>Belum Di Isi</span>
    </div>
@endforelse



<script>
    document.addEventListener('alpine:init', () => {
            Alpine.data('filePreview', (initialImageUrl) => ({
                imageUrl: initialImageUrl,  // Initialize with the existing image URL or null if none
                handleFilePreview(event) {
                    const file = event.target.files[0];
                    console.log(file, imageUrl);

                    if (file) {
                        this.imageUrl = URL.createObjectURL(file);  // Update image preview with selected file
                    } else {
                        this.imageUrl = initialImageUrl;  // Revert to the original image if no file is selected
                    }
                },
            }));
    });

    // Select all elements whose IDs contain the word 'map'
    const mapElements = document.querySelectorAll('[id*="map"]');

    // Iterate over each element and set its height to 180px
    mapElements.forEach(element => {
        element.style.height = '180px';
    });


    @foreach ($bellow as $index => $dataItem)
        initializeMap('map1_{{ $index }}', 'maps_tiba_{{ $index }}', '{{ $dataItem->maps_tiba ?: '0,0'  }}');
    @endforeach

    function initializeMap(mapId, inputId, coordinates) {
        // Parse the coordinates string into latitude and longitude
        var coordsArray = coordinates.split(',');
        var latitude = parseFloat(coordsArray[0]);
        var longitude = parseFloat(coordsArray[1]);

        // console.log(inputId, latitude, longitude);


        // Initialize the map centered at the specified coordinates
        var map = L.map(mapId).setView([latitude, longitude], 13);

        // Add a tile layer to the map
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Add a marker at the specified coordinates
        L.marker([latitude, longitude]).addTo(map);
    }
</script>
