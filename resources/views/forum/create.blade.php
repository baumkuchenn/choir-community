@extends('layouts.forum')

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ route('forum.index') }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Isi Detail Forum</h2>
        
        <form method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data" class="mb-0">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Forum</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="foto_profil" class="form-label">Foto Profil Forum</label>
                    <div class="d-flex align-items-center gap-3">
                        <img id="fotoProfilPreview" src="{{ old('foto_profil') }}" alt="Foto Profil" style="width: 100px; height: 100px;">
                        <div class="text-center">
                            <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                            <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                        </div>
                    </div>
                    <small class="fw-light">Rekomendasi foto memiliki aspek ratio 1:1</small>
                    @error('foto_profil')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="foto_banner" class="form-label">Foto Banner Forum</label>
                    <div class="d-flex align-items-center gap-3">
                        <img id="fotoBannerPreview" src="{{ old('foto_banner') }}" alt="Foto Banner" style="width: 160px; height: 90px;">
                        <div class="text-center">
                            <input type="file" class="form-control" id="foto_banner" name="foto_banner" accept="image/*">
                            <small class="fw-light">JPG, JPEG, atau PNG. Max 2 MB</small>
                        </div>
                    </div>
                    <small class="fw-light">Rekomendasi foto memiliki aspek ratio 16:9</small>
                    @error('foto_banner')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="visibility" class="form-label">Tipe Forum</label>
                    <select class="form-select" id="visibility" name="visibility" required>
                        <option value="" disabled selected>Pilih Tipe</option>
                        <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Publik</option>
                        <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Privat</option>
                    </select>
                    @error('visibility')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="deskripsi" class="form-label">Deskripsi Forum</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" maxlength="250" rows="10" required>{!! old('deskripsi') !!}</textarea>
                    <div class="d-flex justify-content-between">
                        <small id="deskripsi-counter">0/250</small>
                    </div>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary w-100 fw-bold mt-2">Simpan Forum</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        //Update preview profil dan banner
        document.getElementById('foto_profil').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoProfilPreview').src = e.target.result; // Set the image source to preview
                };
                reader.readAsDataURL(file); // Convert the file to Base64
            }
        });

        document.getElementById('foto_banner').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('fotoBannerPreview').src = e.target.result; // Set the image source to preview
                };
                reader.readAsDataURL(file); // Convert the file to Base64
            }
        });


        //Counter Deskripsi
        const deksripsiInput = document.getElementById("deskripsi");
        const deskripsiCount = document.getElementById("deskripsi-counter");

        function updateCounter(inputElement, counterElement) {
            counterElement.textContent = `${inputElement.value.length}/250 karakter`;
        }

        updateCounter(deksripsiInput, deskripsiCount);

        deksripsiInput.addEventListener("input", function() {
            updateCounter(deksripsiInput, deskripsiCount);
        });
    });
</script>
@endsection