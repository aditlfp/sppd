<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form SPPD') }}
        </h2>
    </x-slot>

    <div class="pb-12 pt-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('main_sppds.store') }}" method="POST" class="form-control">
                        @csrf
                        <div class="mb-4">
                            <label for="auth_official" class="block text-sm font-medium text-gray-700 required label-text">Yang Memberi Perintah</label>
                            <select name="auth_official" id="auth_official" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Memberi Perintah</option>
                                @forelse ($user as $s)
                                    @if ($s->jabatan_id == 3 || $s->jabatan_id == 24)
                                        <option value={{ $s->nama_lengkap }}> {{ $s->nama_lengkap }} </option>
                                    @endif
                                @empty
                                    <option selected disabled>- Kosong -</option>
                                @endforelse
                            </select>


                        </div>
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 required label-text">Nama Yang Diperintah</label>
                            <select name="user_id" id="user_id" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Diperintah</option>
                            @forelse ($user as $s)
                                <option value="{{ $s->id }}" data-jabatan-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 required label-text">Jabatan</label>
                            <select id="jabatan" @readonly(true) required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Jabatan</option>
                            @forelse ($user as $s)
                                <option disabled value="{{ $s->id }}" data-jabatan-real="{{ $s->jabatan_id }}"> {{ $s->jabatan?->name_jabatan }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        {{-- eslon --}}
                        <div class="mb-4">
                            <label for="eslon_id" class="block text-sm font-medium text-gray-700 required label-text">Eslon</label>
                            <select name="eslon_id" @readonly(true) disabled id="eslon_id" @readonly(true) required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eslon</option>
                            @forelse ($eslon as $s)
                                @if ($s->jabatan_id != null)
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        <option disabled value="{{ $s->id }}" data-jabatan-id="{{ $itemOK->id }}"> {{ $s->name }} </option>
                                    @endforeach
                                @endif
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="maksud_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Maksud Perjalanan Dinas</label>
                            <textarea name="maksud_perjalanan" id="maksud_perjalanan" rows="2" required placeholder="Maksud Perjalanan Dinas..." class="mt-1 block rounded-sm textarea textarea-bordered textarea-sm w-full"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="alat_angkutan" class="block text-sm font-medium text-gray-700 required label-text">Alat Angkutan</label>
                            <div class="flex items-center w-full gap-x-3">
                                <input type="checkbox" name="alat_angkutan" id="alat_angkutan" class="mt-1 block checkbox rounded-sm" required>
                                <span>Kendaraan Kantor : 60000</span>
                            </div>

                            <div class="flex items-center w-full gap-x-3">
                                <input type="checkbox" name="alat_angkutan" id="alat_angkutan2" class="mt-1 block checkbox rounded-sm" required>
                                <span>Kendaraan Umum : 60000</span>
                            </div>

                        </div>

                        <div class="mb-4">
                            <label for="tempat_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tempat Berangkat</label>
                            <input type="text" name="tempat_berangkat" id="tempat_berangkat" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="Tempat Berangkat">
                        </div>
                        <div id="map"></div>
                        <div class="mb-4 hidden">
                            <label for="maps_berangkat" class="block text-sm font-medium text-gray-700 ">Maps Berangkat</label>
                            <input type="text" readonly name="maps_berangkat" id="maps_berangkat" class="mt-1 block w-full" required>
                        </div>


                        <div class="mb-4">
                            <label for="tempat_tujuan" class="block text-sm font-medium text-gray-700 required label-text">Tempat Tujuan</label>
                            <input type="text" name="tempat_tujuan" id="tempat_tujuan" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="Tempat Tujuan">
                        </div>

                        <div class="mb-4">
                            <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Lama Perjalanan</label>
                            <div class="flex w-full gap-x-3 items-center">
                                <input type="number" name="lama_perjalanan" id="lama_perjalanan" class="mt-1 block input input-sm input-bordered text-xs rounded-sm w-full" required><span>Hari</span>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="date_time_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Date Time Berangkat - Kembali</label>
                            <div class="flex w-full items-center gap-x-1">
                                <input type="date" name="date_time_berangkat" id="date_time_berangkat" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                                <span class="w-[5%] text-center">-</span>
                                <input type="date" name="date_time_kembali" id="date_time_kembali" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="nama_pengikut" class="block text-sm font-medium text-gray-700 label-text">Nama Pengikut</label>
                            <select name="nama_pengikut" id="nama_pengikut" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Nama Pengikut</option>
                            @forelse ($user as $s)
                                <option value="{{ $s->nama_lengkap }}" data-jabatan-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jabatan_pengikut" class="block text-sm font-medium text-gray-700 label-text">Jabatan Pengikut</label>
                            <select name="jabatan_pengikut" @readonly(true) disabled id="jabatan_pengikut" @readonly(true) required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eslon</option>
                            @forelse ($eslon as $s)
                                @if ($s->jabatan_id != null)
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        <option disabled value="{{ $s->id }}" data-jabatan-id="{{ $itemOK->id }}"> {{ $s->name }} </option>
                                    @endforeach
                                @endif
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="budget_id" class="block text-sm font-medium text-gray-700 label-text">Budget ID</label>
                            <input type="number" name="budget_id" id="budget_id" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="e_toll" class="block text-sm font-medium text-gray-700 label-text">E-Toll</label>
                            <input type="checkbox" name="e_toll" id="e_toll" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="makan" class="block text-sm font-medium text-gray-700 label-text">Makan</label>
                            <input type="checkbox" name="makan" id="makan" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="lain_lain_desc" class="block text-sm font-medium text-gray-700 label-text">Lain-lain Desc</label>
                            <textarea name="lain_lain_desc" id="lain_lain_desc" class="mt-1 block w-full textarea textarea-bordered textarea-sm"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="lain_lain" class="block text-sm font-medium text-gray-700 label-text">Lain-lain</label>
                            <input type="checkbox" name="lain_lain" id="lain_lain" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="date_time_arrive" class="block text-sm font-medium text-gray-700 label-text">Date Time Arrive</label>
                            <input type="datetime-local" name="date_time_arrive" id="date_time_arrive" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="arrive_at" class="block text-sm font-medium text-gray-700 label-text">Arrive At</label>
                            <input type="text" name="arrive_at" id="arrive_at" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="foto_arrive" class="block text-sm font-medium text-gray-700 label-text">Foto Arrive</label>
                            <input type="file" name="foto_arrive" id="foto_arrive" class="mt-1 block w-full file-input file-input-bordered">
                        </div>
                        <div class="mb-4">
                            <label for="continue" class="block text-sm font-medium text-gray-700 label-text">Continue</label>
                            <input type="checkbox" name="continue" id="continue" class="mt-1 block checkbox rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="date_time_destination" class="block text-sm font-medium text-gray-700 label-text">Date Time Destination</label>
                            <input type="datetime-local" name="date_time_destination" id="date_time_destination" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm">
                        </div>
                        <div class="mb-4">
                            <label for="foto_destination" class="block text-sm font-medium text-gray-700 label-text">Foto Destination</label>
                            <input type="file" name="foto_destination" id="foto_destination" class="mt-1 block w-full file-input file-input-bordered">
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
    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            var selectedUser = this.options[this.selectedIndex];
            var selectedJabatanId = selectedUser.getAttribute('data-jabatan-id');
            var eslonSelect = document.getElementById('eslon_id');
            var eslonSelect1 = document.getElementById('jabatan');
            var foundJabatan = false;
            var foundEslon = false;

            for (var i = 0; i < eslonSelect.options.length; i++) {
                var option = eslonSelect.options[i];
                if (option.getAttribute('data-jabatan-id') == selectedJabatanId) {
                    option.selected = true;
                    foundEslon = true;
                    break;
                }
            }

            // console.log(foundEslon, option.selectedIndex);


            if(foundEslon == false)
            {
                option.value = 'Pilih Eslon'
                eslonSelect.selectedIndex = 0;
            }

            for (var i = 0; i < eslonSelect1.options.length; i++) {
                var option1 = eslonSelect1.options[i];
                if (option1.getAttribute('data-jabatan-real') == selectedJabatanId) {
                    option1.selected = true;
                    break;
                }else{
                    option1.selected = false;
                }
            }
        });

        var map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        map.locate({setView: true, maxZoom: 16});

        function onLocationFound(e) {
            L.marker(e.latlng).addTo(map)
                .bindPopup("You In Here !").openPopup();
            console.log(e.latlng);
            document.getElementById('maps_berangkat').value = e.latlng.lat + ',' + e.latlng.lng;
        }

        map.on('locationfound', onLocationFound);

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationerror', onLocationError);
    </script>
</x-app-layout>
