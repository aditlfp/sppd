<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Edit SPPD') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('main_sppds.change', $mainSppd->id) }}" method="POST" class="form-control"
                        x-data="{
                            transportType: {{ $mainSppd->alat_angkutan }},
                            useManual: {{ $mainSppd->nama_kendaraan_lain ? 'true' : 'false' }},
                            dataT: {{$transportations->toJson()}},
                            mainSppd: {{$mainSppd->toJson()}},
                            uangSaku: 0,
                            tol: {{ $mainSppd->e_toll }},
                            makan: {{ $mainSppd->makan }},
                            lainLain: {{ json_encode($mainSppd->lain_lain) }},
                            lainLainInputs: [],
                            alatAngkutan1: {{ $mainSppd->nama_kendaraan_lain ? $mainSppd->alat_angkutan : 0 }},
                            transport: this.useManual ? this.transportType : this.alatAngkutan1,
                            total: 0,
                            initLainLain() {
                                const values = {{ json_encode($mainSppd->lain_lain ?? []) }};
                                const descriptions = {{ json_encode($mainSppd->lain_lain_desc ?? []) }};

                                if (values.length > 0) {
                                    for (let i = 0; i < values.length; i++) {
                                        this.lainLainInputs.push({
                                            id: Date.now() + i,
                                            value: values[i],
                                            description: descriptions[i] || ''
                                        });
                                    }
                                }
                            },
                            calculateTotal() {
                                this.total = (this.uangSaku = parseFloat(document.getElementById('uang_saku').value) || 0) +
                                (parseFloat(this.tol) || 0) +
                                (parseFloat(this.makan) || 0) +
                                (parseFloat(this.transport) || 0) +
                                (this.lainLainInputs.filter(item => item.value).reduce((sum, item) => sum + parseFloat(item.value), 0) || 0);
                            }
                        }"
                        x-init="transport = useManual ? transportType : alatAngkutan1; initLainLain(); calculateTotal();">
                        @method('PATCH')
                        @csrf
                        <div class="mb-4">
                            <label for="auth_official" class="block text-sm font-medium text-gray-700 required label-text">Yang Memberi Perintah</label>
                            <select name="auth_official" id="auth_official" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Memberi Perintah</option>
                                @forelse ($user as $s)
                                    @if ($s->jabatan_id == 3 || $s->jabatan_id == 24)
                                        <option {{ normalizeString($s->nama_lengkap) === normalizeString($mainSppd->auth_official) ? "selected" : "" }} value="{{ $s->nama_lengkap }}"> {{ $s->nama_lengkap }} </option>
                                    @endif
                                @empty
                                    <option selected disabled>- Kosong -</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('auth_official')" class="mt-2" />


                        </div>
                        {{-- nama yang diperintah --}}
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 required label-text">Nama Yang Diperintah</label>
                            <select name="user_id" id="user_id" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Diperintah</option>
                            @forelse ($user as $s)
                                <option {{ $s->id == $mainSppd->user_id ? "selected" : "" }} value="{{ $s->id }}" data-jabatan-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>
                        {{-- jabatan --}}
                        <div class="mb-4">
                            <label for="jabatan" class="block text-sm font-medium text-gray-700 required label-text">Jabatan</label>
                            <select @readonly(true) id="jabatan"  required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Jabatan</option>
                            @forelse ($user as $s)
                                <option disabled {{ $s->id == $mainSppd->user_id ? 'selected' : '' }} value="{{ $s->id }}" data-jabatan-real="{{ $s->jabatan_id }}"> {{ $s->jabatan?->name_jabatan }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />

                        </div>
                        {{-- eslon --}}
                        <div class="mb-4">
                            <label for="eslon_id" class="block text-sm font-medium text-gray-700 required label-text">Eselon</label>
                            <select @readonly(true) name="eslon_id" id="eslon_id" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eselon</option>
                                @forelse ($eslon as $s)
                                @if (!is_null($s->jabatan_id))
                                @php
                                $jabatanIds = is_array($s->jabatan_id) ? $s->jabatan_id : explode(',', $s->jabatan_id);
                            @endphp
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = $jabatanData[$item] ?? null;
                                        @endphp

                                        @if ($itemOK)
                                            <option {{ $s->id == $mainSppd->eslon_id ? "selected" : "" }} value="{{ $s->id }}" data-jabatan-id="{{ $itemOK->id }}"> {{ $s->name }} </option>
                                        @endif
                                    @endforeach
                                @endif
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('eslon_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="nama_pengikut" class="block text-sm font-medium text-gray-700 label-text">Nama Pengikut</label>
                            <select name="nama_pengikut" id="nama_pengikut" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Nama Pengikut</option>
                                @forelse ($user as $s)
                                    <option {{ normalizeString($s->nama_lengkap) === normalizeString($mainSppd->nama_pengikut) ? "selected" : "" }} value="{{ $s->nama_lengkap }}" data-jb-peng-id="{{ $s->jabatan_id }}">
                                        {{ $s->nama_lengkap }}
                                    </option>
                                @empty
                                    <option selected disabled>- Kosong -</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="jabatan_pengikut" class="block text-sm font-medium text-gray-700 label-text">Eselon Pengikut</label>
                            <select name="jabatan_pengikut" id="jabatan_pengikut" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eselon</option>
                                @foreach ($eslon as $s)

                                @if (!empty($s->jabatan_id))
                                    @php
                                        // Ubah jabatan_id ke array jika masih dalam format string
                                        $jabatanIds = is_array($s->jabatan_id) ? $s->jabatan_id : explode(',', $s->jabatan_id);
                                    @endphp
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        @if ($itemOK)
                                            <option {{ $s->id == $mainSppd->jabatan_pengikut ? "selected" : "" }} value="{{ $s->id }}" data-jabatan-peng-id="{{ $itemOK->id }}">
                                                {{ $s->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            </select>
                        </div>


                        <div class="mb-4">
                            <label for="maksud_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Maksud Perjalanan Dinas</label>
                            <textarea name="maksud_perjalanan" id="maksud_perjalanan" rows="2" required placeholder="Maksud Perjalanan Dinas..." class="mt-1 block rounded-sm textarea textarea-bordered textarea-sm w-full">{{ $mainSppd->maksud_perjalanan }}</textarea>
                            <x-input-error :messages="$errors->get('maksud_perjalanan')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="alat_angkutan" class="block text-sm font-medium text-gray-700 required label-text">Alat Angkutan</label>
                            <template x-for="item in dataT" >
                                <div class="flex items-center w-full gap-x-3">
                                    <input type="radio" name="alat_angkutan" x-model="transportType" @change="transport = transportType; useManual = false; calculateTotal();" :value="item.anggaran" :required="!useManual" class="mt-2 radio bg-blue-100 border-blue-300">
                                    <span class="capitalize mt-2" x-text="item.jenis + ' - ' + item.nama_kendaraan + ' : ' + item.anggaran"></span>
                                </div>
                            </template>

                            <div>
                                <div class="flex items-center gap-x-3">
                                    <input type="radio" x-model="transportType"
                                    :value="transportType"
                                    @change="useManual = true; transport = alatAngkutan1; calculateTotal();" 
                                    id="transportOther" class="mt-2 radio bg-blue-100 border-blue-300">
                                    <div class="flex w-full">
                                        <input type="text" name="nama_kendaraan_lain"  class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm mr-3" placeholder="Nama Kendaraan Yang Digunakan" :disabled="!useManual" value="{{ $mainSppd->nama_kendaraan_lain ? $mainSppd->nama_kendaraan_lain : ''}} ">

                                        <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                        <input type="text" id="alat_angkutan_1" x-model="alatAngkutan1" @input="transport = alatAngkutan1; calculateTotal();" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" :required="useManual" placeholder="Rp. 1.000.000"  :disabled="!useManual">
                                    </div>
                                </div>
                                <!-- HIDDEN INPUT yang akan dikirim -->
                                <input type="hidden" name="alat_angkutan" :value="useManual ? alatAngkutan1 : transportType">
                            </div>
                            <x-input-error :messages="$errors->get('alat_angkutan')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="tempat_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tempat Berangkat</label>
                            <input type="text"  name="tempat_berangkat" id="tempat_berangkat" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="Tempat Berangkat">
                            <x-input-error :messages="$errors->get('tempat_berangkat')" class="mt-2" />
                        </div>
                        <div id="map"></div>
                        <div class="mb-4 hidden">
                            <label for="maps_berangkat" class="block text-sm font-medium text-gray-700 ">Maps Berangkat</label>
                            <input type="text" readonly name="maps_berangkat" id="maps_berangkat" class="mt-1 block w-full" required>
                        </div>

                        <div class="my-4">
                            <label for="tempat_tujuan" class="block text-sm font-medium text-gray-700 required label-text">Tempat Tujuan</label>
                            <select name="tempat_tujuan" id="tempat_tujuan" @change="calculateTotal();" class="select select-bordered select-sm w-full text-xs rounded-sm mt-1">
                                    <option selected disabled>-Pilih Wilayah-</option>
                                @forelse($regions as $reg)
                                    <option {{ normalizeString($reg->nama_daerah) === normalizeString($mainSppd->tempat_tujuan) ? "selected" : ""}} data-reg-id="{{ $reg->id }}" value="{{ $reg->nama_daerah }}">{{ $reg->name . " - " . $reg->nama_daerah }}</option>
                                @empty

                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('tempat_tujuan')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Lama Perjalanan</label>
                            <div class="flex w-full items-center">
                                <input type="number" value="{{ $mainSppd->lama_perjalanan}}" name="lama_perjalanan" id="lama_perjalanan" class="mt-1 block input input-sm input-bordered text-xs rounded-sm rounded-r-none border-r-0 w-full" placeholder="10" required>
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs rounded-l-none rounded-sm w-12 border-l-0 disabled:border-[#D4D4D4]" required value="Hari">
                                <x-input-error :messages="$errors->get('lama_perjalanan')" class="mt-2" />
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="date_time_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tanggal Berangkat - Kembali</label>
                            <div class="flex w-full items-center gap-x-1">
                                <input type="date" value="{{ $mainSppd->date_time_berangkat }}" name="date_time_berangkat" id="date_time_berangkat" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                                <span class="w-[5%] text-center">-</span>
                                <input type="date" value="{{ $mainSppd->date_time_kembali }}" name="date_time_kembali" id="date_time_kembali" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                            </div>
                            <x-input-error :messages="$errors->get('date_time_berangkat')" class="mt-2" />
                            <x-input-error :messages="$errors->get('date_time_kembali')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <label for="budget_id" class="block text-sm font-medium text-gray-700 label-text required">Uang Saku</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" value="{{ $mainSppd->uang_saku }}" name="uang_saku" id="uang_saku" x-model.lazy="uangSaku" @input="calculateTotal()" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" required placeholder="Rp. 1.000.000" readonly>
                                <x-input-error :messages="$errors->get('uang_saku')" class="mt-2" />
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="e_toll" class="block text-sm font-medium text-gray-700 label-text">E-Toll <span class="text-red-500 italic">( opsional )</span></label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" value="{{ $mainSppd->e_toll }}" name="e_toll" id="e_toll" x-model.lazy="tol" @input="calculateTotal()" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" placeholder="1.000.000">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="makan" class="block text-sm font-medium text-gray-700 label-text required">Makan</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" value={{ $mainSppd->makan }} name="makan" id="makan" x-model="makan" @input="calculateTotal()" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" required placeholder="1.000.000">
                                <x-input-error :messages="$errors->get('makan')" class="mt-2" />
                            </div>
                        </div>
                        <div

                        class="mb-4">
                        <label for="lain_lain" class="block text-sm font-medium text-gray-700 label-text">Lain - Lain <span class="text-red-500 italic">( opsional )</span></label>

                        <template x-for="(input, index) in lainLainInputs" :key="input.id">
                            <div class="mt-2">
                                <div class="flex flex-col">
                                    <input
                                        name="lain_lain_desc[]"
                                        x-model="lainLainInputs[index].description"
                                        class="mt-1 block w-full input input-bordered input-sm rounded-sm"
                                        placeholder="Lain Lain"
                                    />

                                    <div class="flex">
                                        <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                        <input
                                            type="text"
                                            name="lain_lain[]"
                                            x-model="lainLainInputs[index].value"
                                            @input="calculateTotal()"
                                            class="mt-1 block input-sm w-full input input-bordered rounded-sm rounded-l-none border-l-0"
                                            placeholder="0"
                                        >
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-error text-white text-sm mt-1 w-full sm:w-auto rounded-sm" @click="lainLainInputs.splice(index, 1)">Hapus</button>
                            </div>
                        </template>

                        <button type="button" class="btn btn-sm w-full bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 text-white text-xs my-2 rounded-sm" @click="lainLainInputs.push({ id: Date.now(), value: '', description: '' })">
                            + Tambah Lain Lain
                        </button>
                    </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 label-text">Total Anggaran</label>
                            <p class="font-semibold text-lg text-blue-600" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total)"></p>
                        </div>

                        <div class="flex items-center justify-end w-full mt-4">
                            <x-primary-button class="w-full" style="background-color: var(--color-warning) !important;">
                                {{ __('Finish & Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
           $('#nama_pengikut').on('change', function() {
            var selectPengikut = this.options[this.selectedIndex];
            var selectedJabatanId = $(selectPengikut).data('jb-peng-id');
            var jabatanPengikut = $('#jabatan_pengikut')[0]; // Get the DOM element

            for (var i = 0; i < jabatanPengikut.options.length; i++) {
                var option = jabatanPengikut.options[i];
                if (option.getAttribute('data-jabatan-peng-id') == selectedJabatanId) {
                    option.selected = true;
                    break;
                }
            }
        });

        $('#tempat_tujuan').on('change', function() {
            var selectTujuan = this.options[this.selectedIndex];
            var selectedRegionId = $(selectTujuan).data('reg-id'); //Wilayah
            // console.log(selectedRegionId);
            var total = 0;
            // FIND ESLON AND REGION ID YANG DIPERINTAH
            // eslon diperintah == eslon budget && eslon pengikut == eslon budget && region sama
            var eslonDipe = $('#eslon_id')[0].options;
            var eslonPeng = $('#jabatan_pengikut')[0].options;

            for(var i = 0; i < eslonPeng.length; i++)
            {
                if(eslonPeng[i].selected)
                {
                    // console.log(eslonPeng[i]);

                    var selectedOption = eslonPeng[i];
                    var isSelectedEslon = selectedOption.value;
                    var budgetOptions = {!! json_encode($budget) !!};

                    for(var j = 0; j < budgetOptions.length; j++)
                    {
                        var bud = budgetOptions[j];
                        if(bud.eslon_id == isSelectedEslon && bud.region_id == selectedRegionId)
                        {
                            total += bud.anggaran;
                            break;
                        }
                    }

                    break;
                }
            }

            for (var i = 0; i < eslonDipe.length; i++) {
                if (eslonDipe[i].selected) {
                    var selectedOption = eslonDipe[i];
                    var isSelectedEslon = selectedOption.value;
                    var budgetOptions = {!! json_encode($budget) !!};

                    for (var j = 0; j < budgetOptions.length; j++) {
                        var bud = budgetOptions[j];
                        if (bud.eslon_id == isSelectedEslon && bud.region_id == selectedRegionId) {
                            total += bud.anggaran;
                            break;
                        }
                    }

                    break;
                }
            }

            $('#uang_saku').val(total);
        });

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
            document.getElementById('maps_berangkat').value = latlng.lat + ',' + latlng.lng;

            // **Get location name using reverse geocoding**
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json`)
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        let locationName = data.address.road || data.address.city || data.address.town || data.address.village || "Unknown Location";
                        // console.log(locationName, data.address);

                        document.getElementById('tempat_berangkat').value = data.address.village + ', ' + data.address.county + ', ' + data.address.state;
                        // userMarker.bindPopup(locationName).openPopup();
                    }
                })
                .catch(error => console.error("Error getting location name:", error));
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
</x-app-layout>
