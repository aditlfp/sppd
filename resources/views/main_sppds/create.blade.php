<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form SPPD') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            @if ($errors->any())
                <div class="m-4 mx-8 flex flex-col gap-y-2">
                        @foreach ($errors->all() as $error)
                            <span class="text-red-500 text-sm italic">{{ $error }} !</span>
                        @endforeach
                </div>
            @endif
                <div class="p-6 text-gray-900">
                    <form action="{{ route('main_sppds.store') }}" method="POST" class="form-control">
                        @csrf
                        <div class="mb-4">
                            <label for="auth_official" class="block text-sm font-medium text-gray-700 required label-text">Yang Memberi Perintah</label>
                            <select name="auth_official" id="auth_official" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Memberi Perintah</option>
                                @forelse ($user as $s)
                                    @if ($s->jabatan_id == 3 || $s->jabatan_id == 24)
                                        <option value="{{ $s->nama_lengkap }}"> {{ $s->nama_lengkap }} </option>
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
                            <select name="eslon_id" id="eslon_id" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eslon</option>
                            @forelse ($eslon as $s)
                                @if ($s->jabatan_id != null)
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        <option value="{{ $s->id }}" data-jabatan-id="{{ $itemOK->id }}"> {{ $s->name }} </option>
                                    @endforeach
                                @endif
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="nama_pengikut" class="block text-sm font-medium text-gray-700 label-text">Nama Pengikut</label>
                            <select name="nama_pengikut" id="nama_pengikut" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Nama Pengikut</option>
                            @forelse ($user as $s)
                                <option value="{{ $s->nama_lengkap }}" data-jb-peng-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jabatan_pengikut" class="block text-sm font-medium text-gray-700 label-text">Jabatan Pengikut</label>
                            <select name="jabatan_pengikut" @readonly(true) disabled id="jabatan_pengikut" @readonly(true) class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Pilih Eslon</option>
                            @forelse ($eslon as $s)
                                @if ($s->jabatan_id != null)
                                    @foreach ($s->jabatan_id as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        <option disabled value="{{ $s->id }}" data-jabatan-peng-id="{{ $itemOK->id }}"> {{ $s->name }} </option>
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
                            @forelse ($transportations as $item)
                            <div class="flex items-center w-full gap-x-3">
                                <input type="radio" name="alat_angkutan" id="alat_angkutan" required class="mt-1 block radio rounded-sm" value="{{ $item->id }}">
                                <span class="capitalize">{{ $item->jenis }} :  {{ toRupiah($item->anggaran)}}</span>
                            </div>
                            @empty

                            @endforelse
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
                            <select name="tempat_tujuan" id="tempat_tujuan" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                    <option selected disabled>-Pilih Wilayah-</option>
                                @forelse($regions as $reg)
                                    <option data-reg-id="{{ $reg->id }}" value="{{ $reg->nama_daerah }}">{{ $reg->name . " - " . $reg->nama_daerah }}</option>
                                @empty

                                @endforelse
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Lama Perjalanan</label>
                            <div class="flex w-full items-center">
                                <input type="number" name="lama_perjalanan" id="lama_perjalanan" class="mt-1 block input input-sm input-bordered text-xs rounded-sm w-full" required>
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs rounded-sm w-12" required value="Hari">
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="date_time_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tanggal Berangkat - Kembali</label>
                            <div class="flex w-full items-center gap-x-1">
                                <input type="date" name="date_time_berangkat" id="date_time_berangkat" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                                <span class="w-[5%] text-center">-</span>
                                <input type="date" name="date_time_kembali" id="date_time_kembali" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="budget_id" class="block text-sm font-medium text-gray-700 label-text required">Uang Saku</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm" value="Rp.">
                                <input type="text" name="uang_saku" id="uang_saku" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="Rp. 1.000.000" readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="e_toll" class="block text-sm font-medium text-gray-700 label-text">E-Toll <span class="text-red-500 italic">( opsional )</span></label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm" value="Rp.">
                                <input type="text" name="e_toll" id="e_toll" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" placeholder="1.000.000">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="makan" class="block text-sm font-medium text-gray-700 label-text required">Makan</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm" value="Rp.">
                                <input type="text" name="makan" id="makan" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="1.000.000">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="lain_lain" class="block text-sm font-medium text-gray-700 label-text">Lain-lain <span class="text-red-500 italic">( opsional )</span> </label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm" value="Rp.">
                                <input type="text" name="lain_lain" id="lain_lain" class="mt-1 block input-sm w-full input input-bordered rounded-sm">
                            </div>

                            <label for="lain_lain_desc" class="block text-sm font-medium text-gray-700 label-text">Deskripsi Lain Lain <span class="text-red-500 italic">( opsional )</span></label>
                            <textarea name="lain_lain_desc" id="lain_lain_desc" class="mt-1 block w-full textarea textarea-bordered textarea-sm rounded-sm"></textarea>
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
            console.log(selectedRegionId);
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


            // for (var i = 0; i < regionId.options.length; i++) {
            //     var option = regionId.options[i];
            //     if (option.getAttribute('value') == selectedRegionId) {
            //         option.selected = true;
            //         break;
            //     }
            // }
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
