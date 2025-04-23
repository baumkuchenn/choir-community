@extends('layouts.management')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Isi Detail Kegiatan Baru</h2>
        
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="mb-0">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Kegiatan</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="parent_kegiatan" class="form-label">Kegiatan Utama</label>
                    <select class="form-select" id="parent_kegiatan" name="parent_kegiatan" data-name="parent" required>
                        <option value="ya" {{ old('parent_kegiatan', 'ya') == 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ old('parent_kegiatan') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('parent_kegiatan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                    <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan" data-name="jenis" required></select>
                    @error('jenis_kegiatan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div id="parent_wrapper">
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="sub_kegiatan_id" class="form-label">Sub Kegiatan Dari</label>
                        <select class="form-select" id="sub_kegiatan_id" name="sub_kegiatan_id">
                            <option value="" disabled selected>Pilih kegiatan utama</option>
                            @foreach ($events as $subEvent)
                                <option value="{{ $subEvent->id }}" {{ old('sub_kegiatan_id') == $subEvent->id ? 'selected' : '' }}>
                                    {{ $subEvent->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_kegiatan_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="seleksi_wrapper">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="tipe_seleksi" class="form-label">Tipe Seleksi</label>
                            <select class="form-select" id="tipe_seleksi" name="tipe_seleksi" data-name="kolaborasi" required>
                                <option value="event" {{ old('tipe_seleksi', 'event') == 'event' ? 'selected' : '' }}>Penyanyi</option>
                                <option value="panitia" {{ old('tipe_seleksi') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                            </select>
                            @error('kegiatan_kolaborasi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div id="non_latihan_wrapper">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" placeholder="" value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" placeholder="" value="{{ old('jam_selesai') }}" required>
                            @error('jam_selesai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="" value="{{ old('lokasi') }}" required>
                            @error('lokasi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="konser_wrapper">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="kegiatan_kolaborasi" class="form-label">Kegiatan Kolaborasi</label>
                                <select class="form-select" id="kegiatan_kolaborasi" name="kegiatan_kolaborasi" data-name="kolaborasi" required>
                                    <option value="ya" {{ old('kegiatan_kolaborasi') == 'ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="tidak" {{ old('kegiatan_kolaborasi', 'tidak') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('kegiatan_kolaborasi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="kolaborasi_wrapper" class="row mb-3">
                            <div class="col-12">
                                <label for="choirs_id" class="form-label">Paduan Suara Kolaborasi</label>
                                <select class="form-select" id="choirs_id" name="choirs_id"></select>
                                @error('choirs_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="peran" class="form-label">Peran dalam Kegiatan</label>
                                <select class="form-select" id="peran" name="peran" data-name="peran" required>
                                    <option value="" disabled selected>Pilih peran dalam kegiatan</option>
                                    <option value="penyanyi" {{ old('peran') == 'penyanyi' ? 'selected' : '' }}>Penyanyi</option>
                                    <option value="panitia" {{ old('peran') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                    <option value="keduanya" {{ old('peran') == 'keduanya' ? 'selected' : '' }}>Penyanyi dan Panitia</option>
                                </select>
                                @error('peran')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <input class="form-check-input" type="checkbox" id="all_notification" name="all_notification" value="ya">
                        <label class="form-check-label" for="all_notification">Kirim notifikasi ke seluruh anggota komunitas.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <input class="form-check-input" type="checkbox" id="parent_notification" name="parent_notification" value="ya">
                        <label class="form-check-label" for="parent_notification">Kirim notifikasi ke anggota komunitas yang ikut dalam kegiatan utama.</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <input class="form-check-input" type="checkbox" id="parent_display" name="parent_display" value="ya">
                        <label class="form-check-label" for="parent_display">Hanya tampilkan kegiatan ke anggota komunitas yang ikut dalam kegiatan utama.</label>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary w-100 fw-bold mt-2">Simpan Kegiatan Baru</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function toggleWrapper(element, wrapper, validValues = []) {
        const elementValue = element.value;
        const inputs = wrapper.querySelectorAll("input, select, textarea");

        if (validValues.includes(elementValue)) {
            wrapper.style.display = "block";
            inputs.forEach(el => el.disabled = false);
        } else {
            wrapper.style.display = "none";
            inputs.forEach(el => {
                el.disabled = true;
                if (el.tagName === "SELECT") {
                    const defaultOption = el.querySelector("option[default], option[selected]");
                    if (defaultOption) {
                        el.value = defaultOption.value;
                    } else {
                        el.selectedIndex = 0;
                    }
                } else {
                    if (el.type === "checkbox" || el.type === "radio") {
                        el.checked = false;
                    } else if (['text', 'number', 'date', 'time'].includes(el.type)) {
                        el.value = "";
                    }
                }
            });
        }
    }


    function updateJenisKegiatanOptions() {
        const parentValue = document.getElementById("parent_kegiatan").value;
        const jenisKegiatanSelect = document.getElementById("jenis_kegiatan");
        const oldJenisKegiatan = "{{ old('jenis_kegiatan') }}";

        const optionsForParent = {
            ya: [
                { value: "internal", text: "Internal" },
                { value: "eksternal", text: "Eksternal" },
            ],
            tidak: [
                { value: "seleksi", text: "Seleksi" },
                { value: "latihan", text: "Latihan" },
                { value: "konser", text: "Konser" },
                { value: "gladi", text: "Gladi Kegiatan" },
                { value: "event", text: "Hari H Kegiatan (Lomba/Job/Event Lain)" },
            ]
        };

        const options = optionsForParent[parentValue] || [];

        // Clear existing options
        jenisKegiatanSelect.innerHTML = "";

        // Add placeholder
        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.disabled = true;
        placeholder.selected = true;
        placeholder.textContent = "Pilih jenis kegiatan";
        jenisKegiatanSelect.appendChild(placeholder);

        // Add new options
        options.forEach(opt => {
            const option = document.createElement("option");
            option.value = opt.value;
            option.textContent = opt.text;

            if (opt.value === oldJenisKegiatan) {
                option.selected = true;
            }

            jenisKegiatanSelect.appendChild(option);
        });

        // If needed, reset selected value
        jenisKegiatanSelect.value = "";
    }

    document.addEventListener("DOMContentLoaded", function () {
        //Wrapper parent kegiatan
        const parent = document.getElementById("parent_kegiatan");
        const parentWrapper = document.getElementById("parent_wrapper");
        toggleWrapper(parent, parentWrapper, ["tidak"]);
        parent.addEventListener("change", function () {
            toggleWrapper(parent, parentWrapper, ["tidak"]);
        });


        //Wrapper jenis konser dan non latihan
        const jenis = document.getElementById("jenis_kegiatan");
        const nonLatihanWrapper = document.getElementById("non_latihan_wrapper");
        toggleWrapper(jenis, nonLatihanWrapper, ["seleksi", "konser", "gladi", "event"]);
        jenis.addEventListener("change", function () {
            toggleWrapper(jenis, nonLatihanWrapper, ["seleksi", "konser", "gladi", "event"]);
        });

        const seleksiWrapper = document.getElementById("seleksi_wrapper");
        toggleWrapper(jenis, seleksiWrapper, ["seleksi"]);
        jenis.addEventListener("change", function () {
            toggleWrapper(jenis, seleksiWrapper, ["seleksi"]);
        });
        
        const konserWrapper = document.getElementById("konser_wrapper");
        toggleWrapper(jenis, konserWrapper, ["konser"]);
        jenis.addEventListener("change", function () {
            toggleWrapper(jenis, konserWrapper, ["konser"]);
        });


        //Wrapper kolaborasi
        const kolaborasi = document.getElementById("kegiatan_kolaborasi");
        const kolaborasiWrapper = document.getElementById("kolaborasi_wrapper");
        toggleWrapper(kolaborasi, kolaborasiWrapper, ["ya"]);
        kolaborasi.addEventListener("change", function () {
            toggleWrapper(kolaborasi, kolaborasiWrapper, ["ya"]);
        });


        //Wrapper peran
        const peran = document.getElementById("peran");
        const peranWrapper = document.getElementById("peran_wrapper");
        toggleWrapper(peran, peranWrapper, ["panitia", "keduanya"]);
        peran.addEventListener("change", function () {
            toggleWrapper(peran, peranWrapper, ["panitia", "keduanya"]);
        });


        //Ubah value jenis kegiatan sesuai parent
        updateJenisKegiatanOptions();
        document.getElementById("parent_kegiatan").addEventListener("change", updateJenisKegiatanOptions);


        //JS Select2 buat padus kolaborasi
        $('#choirs_id').select2({
            placeholder: 'Cari Komunitas Paduan Suara...',
            width: '100%',
            ajax: {
                url: '{{ route("events.search.choir") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
    });
</script>
@endsection