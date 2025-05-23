<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan SPPD') }}
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
                    <form id="sppdForm" action="{{ route('verifyUpdate', $mainSppd->id)}}" method="post"
                        x-data="{
                            total: 0,
                            dataT: {{ $transportations->toJson() }},
                            mainSppd: {{ $mainSppd->toJson() }},
                            lainLainValues: {{ json_encode($mainSppd->lain_lain ?? []) }},
                            transport: {{ $mainSppd->nama_kendaraan_lain ? 0 : $mainSppd->alat_angkutan }},
                            alatAngkutan1: {{ $mainSppd->nama_kendaraan_lain ? $mainSppd->alat_angkutan : 0 }},
                            total: 0,
                            calculateTotal() {
                                // Sum up all lain_lain values
                                const lainLainTotal = this.lainLainValues.reduce((sum, value) => {
                                    return sum + (parseFloat(value) || 0);
                                }, 0);

                                this.total = (parseFloat(this.mainSppd.uang_saku) || 0) +
                                            (parseFloat(this.mainSppd.tol) || 0) +
                                            (parseFloat(this.mainSppd.makan) || 0) +
                                            lainLainTotal +
                                            (parseFloat(this.transport) || 0) +
                                            (parseFloat(this.alatAngkutan1) || 0);
                            }
                        }"
                        x-init="calculateTotal()">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="auth_official" class="block text-sm font-medium text-gray-700 required label-text">Yang Memberi Perintah</label>
                            <select name="auth_official" id="auth_official" required class="select select-bordered select-sm w-full text-xs rounded-sm">
                                <option selected disabled>Yang Memberi Perintah </option>
                                @forelse ($user as $s)
                                @if ($s->jabatan_id == 3 || $s->jabatan_id == 24)

                                        <option {{ normalizeString($s->nama_lengkap) === normalizeString($mainSppd->auth_official) ? "selected" : "" }} value="{{ $s->nama_lengkap }}">  {{ $s->nama_lengkap }} </option>
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
                                <option {{ $s->id == $mainSppd->user_id ? "selected" : "" }}  value="{{ $s->id }}" data-jabatan-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
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
                                <option {{ $s->id == $mainSppd->user_id ? 'selected' : '' }} disabled value="{{ $s->id }}" data-jabatan-real="{{ $s->jabatan_id }}"> {{ $s->jabatan?->name_jabatan }} </option>
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
                                @if (!is_null($s->jabatan_id))
                                    @php
                                        $jabatanIds = is_array($s->jabatan_id) ? $s->jabatan_id : explode(',', $s->jabatan_id);
                                    @endphp

                                    @foreach ($jabatanIds as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        @if ($itemOK)
                                            <option {{ $s->id == $mainSppd->eslon_id ? "selected" : "" }}
                                                    value="{{ $s->id }}"
                                                    data-jabatan-id="{{ $itemOK->id }}">
                                                {{ $s->name }}
                                            </option>
                                        @endif
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
                                <option {{ normalizeString($s->nama_lengkap) === normalizeString($mainSppd->nama_pengikut) ? "selected" : "" }}  value="{{ $s->nama_lengkap }}" data-jb-peng-id="{{ $s->jabatan_id }}"> {{ $s->nama_lengkap }} </option>
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
                                @if (!is_null($s->jabatan_id))
                                    @php
                                        // Ubah jabatan_id ke array jika masih dalam format string
                                        $jabatanIds = is_array($s->jabatan_id) ? $s->jabatan_id : explode(',', $s->jabatan_id);
                                    @endphp

                                    @foreach ($jabatanIds as $item)
                                        @php
                                            $itemOK = App\Models\Jabatan::find($item);
                                        @endphp

                                        @if ($itemOK)
                                            <option {{ $s->id == $mainSppd->jabatan_pengikut ? "selected" : "" }}
                                                    disabled
                                                    value="{{ $s->id }}"
                                                    data-jabatan-peng-id="{{ $itemOK->id }}">
                                                {{ $s->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            @empty
                                <option selected disabled>- Kosong -</option>
                            @endforelse

                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="maksud_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Maksud Perjalanan Dinas</label>
                            <textarea name="maksud_perjalanan" id="maksud_perjalanan" rows="2" required placeholder="Maksud Perjalanan Dinas..." class="mt-1 block rounded-sm textarea textarea-bordered textarea-sm w-full">{{ $mainSppd->maksud_perjalanan }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="alat_angkutan" class="block text-sm font-medium text-gray-700 required label-text">Alat Angkutan</label>
                            @forelse ($transportations as $item)
                            <div class="flex items-center w-full gap-x-3">
                                <input type="radio" name="alat_angkutan" id="alat_angkutan" required class="mt-2 radio bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600" {{ $mainSppd->nama_kendaraan_lain ? ($item->anggaran == $mainSppd->alat_angkutan ? "checked" : "") : "" }}
                                value="{{ $item->id }}">
                                <span class="capitalize">{{ $item->jenis }} :  {{ toRupiah($item->anggaran)}}</span>
                            </div>
                            @empty

                            @endforelse

                            <div>
                                <div class="flex items-center gap-x-3">
                                    <input type="radio" {{ $mainSppd->nama_kendaraan_lain ? 'checked' : ''}} name="alat_angkutan" id="transportOther" class="mt-2 radio bg-blue-100 border-blue-300">
                                    <div class="flex w-full">
                                        <input type="text" name="nama_kendaraan_lain"  class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm mr-3" placeholder="Nama Kendaraan Yang Digunakan" value="{{ $mainSppd->nama_kendaraan_lain ? $mainSppd->nama_kendaraan_lain : ''}} ">

                                        <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                        <input type="text" name="alat_angkutan" id="alat_angkutan_1" x-model="alatAngkutan1" @input="calculateTotal()" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" required placeholder="Rp. 1.000.000" >
                                    </div>
                                </div>
                        </div>

                        <div class="mb-4">
                            <label for="tempat_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tempat Berangkat</label>
                            <input type="text" value="{{ $mainSppd->tempat_berangkat }}"  name="tempat_berangkat" id="tempat_berangkat" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm" required placeholder="Tempat Berangkat">
                        </div>
                        <div id="map"></div>
                        <div class="mb-4 hidden">
                            <label for="maps_berangkat" class="block text-sm font-medium text-gray-700 ">Maps Berangkat</label>
                            <input type="text" readonly name="maps_berangkat" id="maps_berangkat" class="mt-1 block w-full" value="{{ $mainSppd->maps_berangkat }}" required>
                        </div>


                        <div class="mb-4">
                            <label for="tempat_tujuan" class="block text-sm font-medium text-gray-700 required label-text">Tempat Tujuan</label>
                            <select name="tempat_tujuan" id="tempat_tujuan" class="select select-bordered select-sm w-full text-xs rounded-sm">
                                    <option selected disabled>-Pilih Wilayah-</option>
                                @forelse($regions as $reg)
                                    <option data-reg-id="{{ $reg->id }}" {{ normalizeString($reg->nama_daerah) === normalizeString($mainSppd->tempat_tujuan) ? "selected" : ""}}  value="{{ $reg->nama_daerah }}">{{ $reg->name . " - " . $reg->nama_daerah }}</option>
                                @empty

                                @endforelse
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="lama_perjalanan" class="block text-sm font-medium text-gray-700 required label-text">Lama Perjalanan</label>
                            <div class="flex w-full items-center">
                                <input type="number" name="lama_perjalanan" id="lama_perjalanan" class="mt-1 block input input-sm input-bordered text-xs rounded-sm rounded-r-none border-r-0 w-full" required value="{{ $mainSppd->lama_perjalanan}}">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs rounded-l-none rounded-sm w-12 border-l-0 disabled:border-[#D4D4D4]" required value="Hari">
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="date_time_berangkat" class="block text-sm font-medium text-gray-700 required label-text">Tanggal Berangkat - Kembali</label>
                            <div class="flex w-full items-center gap-x-1">
                                <input type="date" name="date_time_berangkat" id="date_time_berangkat" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required value="{{ $mainSppd->date_time_berangkat }}">
                                <span class="w-[5%] text-center">-</span>
                                <input type="date" name="date_time_kembali" id="date_time_kembali" class="mt-1 block w-[47.5%] input input-sm input-bordered text-xs rounded-sm" required value="{{ $mainSppd->date_time_kembali }}">
                            </div>
                        </div>


                        <div class="mb-4">
                            <label for="budget_id" class="block text-sm font-medium text-gray-700 label-text required">Uang Saku</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" name="uang_saku" id="uang_saku" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" required placeholder="Rp. 1.000.000" readonly value="{{ $mainSppd->uang_saku }}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="e_toll" class="block text-sm font-medium text-gray-700 label-text">E-Toll <span class="text-red-500 italic">( opsional )</span></label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" name="e_toll" id="e_toll" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" value="{{ $mainSppd->e_toll }}" placeholder="1.000.000">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="makan" class="block text-sm font-medium text-gray-700 label-text required">Makan</label>
                            <div class="flex">
                                <input type="text" disabled class="mt-1 block input input-sm input-bordered text-xs w-12 rounded-sm disabled:border-[#D4D4D4] rounded-r-none border-r-0" value="Rp.">
                                <input type="text" name="makan" id="makan" class="mt-1 block w-full input input-sm input-bordered text-xs rounded-sm rounded-l-none border-l-0" required value={{ $mainSppd->makan }} placeholder="1.000.000">
                            </div>
                        </div>
                        {{-- $mainSppd->lain_lain  --}}

                        <div x-data="{
                            lainLainInputs: [],
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
                            }
                        }"
                        x-init="initLainLain()"
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
                                            x-model.lazy="lainLainInputs[index].value"
                                            @input="calculateTotal()"
                                            class="mt-1 block input-sm w-full input input-bordered rounded-sm rounded-l-none border-l-0"
                                            placeholder="0"
                                        >
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" disabled class="btn btn-sm w-full bg-blue-500 hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 text-white text-xs my-2 rounded-sm hover:cursor-not-allowed" @click="lainLainInputs.push({ id: Date.now(), value: '', description: '' })">
                            + Tambah Lain Lain
                        </button>
                    </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 label-text">Total Anggaran</label>
                            <p class="font-semibold text-lg text-blue-600" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total)"></p>
                        </div>

                        <input type="hidden" name="name_verify" id="name_verify" value="">

                        <div class="flex flex-col justify-end mt-4 gap-x-4 gap-y-2">
                        @if ($mainSppd->verify == "0" || $mainSppd->verify == null)
                                <button type="button" onclick="submitForm('verify_departure')" class="btn btn-sm btn-primary rounded-sm sm:px-10">
                                    <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM17.4571 9.45711L11 15.9142L6.79289 11.7071L8.20711 10.2929L11 13.0858L16.0429 8.04289L17.4571 9.45711Z"></path>
                                    </svg>
                                    Verify SPPD
                                </button>

                                <button type="button" onclick="submitForm('reject')" class="btn btn-sm btn-error text-white rounded-sm">
                                <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 10.5858L9.17157 7.75736L7.75736 9.17157L10.5858 12L7.75736 14.8284L9.17157 16.2426L12 13.4142L14.8284 16.2426L16.2426 14.8284L13.4142 12L16.2426 9.17157L14.8284 7.75736L12 10.5858Z"></path></svg>
                                    Reject SPPD
                                </button>
                        @elseif ($mainSppd->verify != "3")
                           @if ($nextSppd && $nextSppd !== "Data Not Found")
                               {!! $nextSppd !!}
                           @else
                            <div class="w-full bg-gray-200 rounded-sm p-4 capitalize text-semibold text-gray-500">
                                <span>data is currently not available</span>
                            </div>
                           @endif
                        @endif
                        <a href="{{ route('main_sppds.index') }}" class="btn btn-sm rounded-sm btn-error my-2 text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5"  viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C17.52 2 22 6.48 22 12C22 17.52 17.52 22 12 22C6.48 22 2 17.52 2 12C2 6.48 6.48 2 12 2ZM12 11V8L8 12L12 16V13H16V11H12Z"></path></svg>
                            BACK</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        function submitForm(action) {
            document.getElementById('name_verify').value = action;
            document.getElementById('sppdForm').submit();
        }

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



        let coordinates = document.getElementById('maps_berangkat').value;
        let [lat, lng] = coordinates.split(",").map(parseFloat);
        var map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
                .bindPopup("You In Here !").openPopup();

        function onLocationError(e) {
            alert(e.message);
        }

        map.on('locationerror', onLocationError);
    </script>
</x-app-layout>
