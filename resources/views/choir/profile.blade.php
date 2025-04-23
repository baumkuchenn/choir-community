@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Profil Komunitas {{ $choir->nama }}</h2>
        <form action="{{ route('choir.update', $choir->id) }}" method="POST" enctype="multipart/form-data" class="mb-0">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Komunitas Paduan Suara</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama', $choir->nama) }}" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama_singkat" class="form-label">Nama Singkatan Komunitas Paduan Suara</label>
                    <input type="text" class="form-control" id="nama_singkat" name="nama_singkat" placeholder="" value="{{ old('nama_singkat', $choir->nama_singkat) }}" required>
                    <small class="fw-light">Nama ini digunakan untuk pembuatan invoice pada e-ticketing konser</small>
                    @error('nama_singkat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="mb-3 mb-lg-0 col-12 col-lg-6">
                    <label for="logo" class="form-label">Logo Komunitas Paduan Suara</label>
                    <div class="image-choir d-flex align-items-center gap-3">
                        <img alt="Logo" style="width: 100px; height: 100px;">
                        <div class="text-center">
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <input type="hidden" name="existing_logo" id="existingLogo" value="{{ $choir->logo ?? '' }}">
                            <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                        </div>
                    </div>
                    <small class="fw-light">Rekomendasi logo memiliki aspek ratio 1:1</small>
                    @error('logo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-lg-6">
                    <label for="profil" class="form-label">Foto Profil Komunitas Paduan Suara</label>
                    <div class="image-choir d-flex align-items-center gap-3">
                        <img alt="profil" style="width: 160px; height: 90px;">
                        <div class="text-center">
                            <input type="file" class="form-control" id="profil" name="profil" accept="image/*">
                            <input type="hidden" name="existing_profil" id="existingProfil" value="{{ $choir->profil ?? '' }}">
                            <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                        </div>
                    </div>
                    <small class="fw-light">Rekomendasi foto memiliki aspek ratio 16:9</small>
                    @error('profil')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="tipe" class="form-label">Tipe Paduan Suara</label>
                    <select class="form-select" id="tipe" name="tipe" required>
                        <option value="">Pilih Tipe</option>
                        <option value="SSAA" {{ old('tipe', $choir->tipe) == 'SSAA' ? 'selected' : '' }}>SSAA (Female Choir)</option>
                        <option value="TTBB" {{ old('tipe', $choir->tipe) == 'TTBB' ? 'selected' : '' }}>TTBB (Male Choir)</option>
                        <option value="SSAATTBB" {{ old('tipe', $choir->tipe) == 'SSAATTBB' ? 'selected' : '' }}>SSAATTBB (Mixed Choir)</option>
                    </select>
                    @error('tipe')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="kotas_id" class="form-label">Kota Beroperasi</label>
                    <select class="form-select" id="kotas_id" name="kotas_id" required></select>
                    <input type="hidden" name="existing_kotas_id" id="existingKotas" value="{{ $choir->kotas_id ?? '' }}">
                    @error('kotas_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="" value="{{ old('alamat', $choir->alamat) }}" required>
                    @error('alamat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="deskripsi" class="form-label">Deskripsi Paduan Suara</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" maxlength="1000" rows="10" required>{!! old('deskripsi', $choir->deskripsi) !!}</textarea>
                    <div class="d-flex justify-content-between">
                        <small class="fw-light">Deskripsi akan ditampilkan ketika membuka seleksi anggota baru</small>
                        <small id="deskripsi-counter">0/1000</small>
                    </div>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <button class="btn btn-primary w-100 fw-bold mt-2">Simpan Seleksi Baru</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Selector untuk asal kota
        let selectedKotaId = document.getElementById('existingKotas').value;

        $('#kotas_id').select2({
            placeholder: 'Cari Kota...',
            width: '100%',
            ajax: {
                url: '{{ route("kota.search") }}',
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
        if (selectedKotaId) {
            $.ajax({
                url: '{{ route("kota.search") }}',
                data: { id: selectedKotaId },
                dataType: 'json',
                success: function(data) {
                    let existingOption = new Option(data.text, data.id, true, true);
                    $('#kotas_id').append(existingOption).trigger('change');
                }
            });
        }


        //Update preview logo dan profil
        document.querySelectorAll(".image-choir").forEach((image) => {
            const fileInput = image.querySelector("input[type='file']");
            const previewImage = image.querySelector("img"); 
            const existingImageInput = image.querySelector("input[type='hidden']");

            let checkImage = existingImageInput.value.trim();
            if (checkImage && checkImage !== "null") {
                let existingImage = `{{ asset('storage/') }}/${checkImage}`;
                previewImage.src = existingImage;
            }

            // Handle new image selection
            fileInput.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result; // Update preview image
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
        

        //Counter Deskripsi
        const deksripsiInput = document.getElementById("deskripsi");
        const deskripsiCount = document.getElementById("deskripsi-counter");

        function updateCounter(inputElement, counterElement) {
            counterElement.textContent = `${inputElement.value.length}/1000 karakter`;
        }

        updateCounter(deksripsiInput, deskripsiCount);

        deksripsiInput.addEventListener("input", function() {
            updateCounter(deksripsiInput, deskripsiCount);
        });
    });
</script>
@endsection