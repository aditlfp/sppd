@if (Route::is('verify_page.*'))
    <h2 class="font-semibold text-md sm:text-xl text-gray-800 leading-tight bg-gray-200 rounded-sm p-4">
        Bukti Keberangkatan & Kedatangan {{ $mainSppd?->maksud_perjalanan }}
    </h2>
@endif

<div class="py-3">
    <div @class([
        '',
        'max-w-7xl mx-auto sm:px-6 lg:px-8' => !Route::is('verify_page.*')
        ])>
        <div
        @class([
            ''.
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
                                <input type="text" hidden value="{{ $mainSppd->code_sppd }}" name="code_sppd">
                                <label for="arrive_at" class="block text-sm font-medium required text-gray-700 label-text">Tiba di</label>
                                <input type="text" name="arrive_at" id="arrive_at" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" placeholder="Lokasi sekarang..." required>
                            </div>
                            <div class="mb-4 w-full">
                                <label for="date_time_arrive" class="block text-sm font-medium required text-gray-700 label-text">Pada Tanggal</label>
                                <input type="text" readonly name="date_time_arrive" id="localDateTime" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                            </div>
                        </div>
                        <div class="mb-4" x-data="filePreview()">
                            <label for="foto_arrive" class="block text-sm font-medium text-gray-700 label-text required">Foto Kedatangan</label>
                            <!-- Preview Section -->
                            <template x-if="imageUrl">
                                <div class="my-2 flex items-center justify-center">
                                    <img :src="imageUrl" alt="Image Preview" class="max-w-[275px] h-auto rounded-sm">
                                </div>
                            </template>
                            <label for="foto_arrive" class="w-full">
                                <div class="min-w-full min-h-16 flex justify-center items-center gap-2 file-input text-blue-600 border-2 border-blue-600 bg-white shadow">
                                    <i class="ri-image-add-line text-lg"></i>
                                    <p class="font-semibold">Tambah Foto</p>
                                </div>
                            </label>
                            <input type="file" required name="foto_arrive" id="foto_arrive" accept="image/*" class="hidden mt-1 w-full file-input file-input-bordered rounded-sm input-sm file-input-primary border-gray-300" @change="handleFilePreview">
                        </div>
                        <div id="map1" class="map-container"></div>
                        <input type="text" name="maps_tiba" id="maps_tiba"  hidden readonly>
                    </div>
                    <div class="border border-gray-200 p-4 mt-4">
                        <div class="mb-4 flex flex-col gap-y-2">
                            <label for="continue" class="block text-sm font-medium text-gray-700 label-text required">Lanjut/Pulang</label>
                            <div class="flex items-center gap-x-2">
                                <input type="radio" name="continue" value="1" {{ $datas === 'true' ? 'checked' : ($datas === 'VERIFIKASI' ? '' : 'disabled') }} class="mt-2 radio bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600">
                                <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Lanjut</label>
                            </div>
                            <div class="flex items-center gap-x-2">
                                <input type="radio" name="continue" value="0" {{ $datas === 'false' ? 'checked' : ($datas === 'VERIFIKASI' ? '' : 'disabled') }} class="mt-2 radio bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600">
                                <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Pulang</label>
                            </div>
                        </div>

                    </div>
                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium text-gray-700 label-text">Note</label>
                        <textarea name="note" id="note" placeholder="Note..." class="mt-1 block w-full textarea textarea-bordered textarea-sm rounded-sm"></textarea>
                    </div>
                        @if (!Route::is('verify_page.*'))
                        <div class="flex items-center justify-end w-full mt-4 gap-2">
                            <x-primary-button class="w-full">
                                {{ __('Save') }}
                            </x-primary-button>
                            <a href="{{ route('main_sppds.index')}}" class="btn btn-sm rounded-sm btn-error w-1/5 text-white">KEMBALI</a>
                        </div>
                        @endif
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filePreview', () => ({
            imageUrl: '',
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
            imageUrl2: '',
            handleFilePreview2(event) {
                const file = event.target.files[0];
                // console.log(file);

                if (file) {
                    this.imageUrl2 = URL.createObjectURL(file);
                } else {
                    this.imageUrl2 = null;
                }
            },
        }));
    });



    function updateDateTime() {
        const now = new Date();
        const formattedDateTime = now.toLocaleString(); // Adjust this for the desired format
        document.getElementById("localDateTime").value = formattedDateTime;
    }

    // Update datetime every second
    setInterval(updateDateTime, 1000);

    // Initialize the datetime immediately
    updateDateTime();
</script>
<script>
        var map = L.map('map1').setView([51.505, -0.09], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var userMarker = null;
        var lastLatLng = null;
        var minDistance = 5; // Minimum movement in meters before updating
        var firstLocationUpdate = true; // Flag to prevent zoom resetting on updates

        function updateLocation(e) {
            var latlng = e.latlng;

            // Ignore small movements to reduce unnecessary updates
            if (lastLatLng) {
                var distance = map.distance(lastLatLng, latlng);
                if (distance < minDistance) {
                    return;
                }
            }

            lastLatLng = latlng; // Update last position

            if (!userMarker) {
                // Create marker if it doesn't exist
                userMarker = L.marker(latlng, { draggable: false }).addTo(map)
                    .bindPopup("You are here!").openPopup();
            } else {
                // Update marker position without changing map center
                userMarker.setLatLng(latlng);
            }

            // Set the map view only on the first location update to avoid zoom bouncing
            if (firstLocationUpdate) {
                map.setView(latlng, 13); // Initial zoom when the location is first found
                firstLocationUpdate = false;
            } else {
                map.panTo(latlng); // Smoothly move the map to the marker without resetting zoom
            }

            // Update the input field with the latest coordinates
            document.getElementById('maps_tiba').value = latlng.lat + ',' + latlng.lng;
        }

        function trackLocation() {
            map.locate({
                watch: true,
                enableHighAccuracy: true
            });
        }

        map.on('locationfound', updateLocation);
        trackLocation(); // Start real-time tracking
</script>
