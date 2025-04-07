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
        <h3 class="mb-3 fw-bold text-center">Data Anggota Komunitas</h3>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary mb-3">Kembali</a>
        <div class="row mb-3">
            <div class="col-6">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="form-control" id="name" name="name">{{ $member->user->name }}</div>
            </div>
            <div class="col-6">
                <label for="no_handphone" class="form-label">Nomor Handphone</label>
                <div class="form-control" id="no_handphone" name="no_handphone">{{ $member->user->no_handphone }}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <div class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                    @if($member->user->jenis_kelamin == 'L')
                        Laki-laki
                    @else
                        Perempuan
                    @endif
                </div>
            </div>
            <div class="col-6">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <div class="form-control" id="tanggal_lahir" name="tanggal_lahir">{{ $member->user->tanggal_lahir }}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <label for="email" class="form-label">Email</label>
                <div class="form-control" id="email" name="email">{{ $member->user->email }}</div>
            </div>
            <div class="col-6">
                <label for="kota" class="form-label">Asal Kota</label>
                <div class="form-control" id="kota" name="kota">{{ $member->user->kota }}</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="alamat" class="form-label">Alamat</label>
                <div class="form-control" id="alamat" name="alamat">{{ $member->user->alamat }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('members.update', $member->id) }}" class="row">
            @csrf
            @method('PUT')
            <h5 class="fw-bold">Roles Dalam Komunitas</h5>
            <div class="row mb-5">
                <div class="col-6">
                    <label for="suara" class="form-label">Kategori Suara</label>
                    <select class="form-select" id="suara" name="suara" required>
                        <option value="" disabled>Pilih Suara</option>
                        @if($choir->tipe == 'SSAA' || $choir->tipe == 'SSAATTBB')
                            <option value="sopran_1" {{ old('suara', $member->suara) == 'sopran_1' ? 'selected' : '' }}>Sopran 1</option>
                            <option value="sopran_2" {{ old('suara', $member->suara) == 'sopran_2' ? 'selected' : '' }}>Sopran 2</option>
                            <option value="alto_1" {{ old('suara', $member->suara) == 'alto_1' ? 'selected' : '' }}>Alto 1</option>
                            <option value="alto_2" {{ old('suara', $member->suara) == 'alto_2' ? 'selected' : '' }}>Alto 2</option>
                        @endif
                        @if($choir->tipe == 'TTBB' || $choir->tipe == 'SSAATTBB')
                            <option value="tenor_1" {{ old('suara', $member->suara) == 'tenor_1' ? 'selected' : '' }}>Tenor 1</option>
                            <option value="tenor_2" {{ old('suara', $member->suara) == 'tenor_2' ? 'selected' : '' }}>Tenor 2</option>
                            <option value="bass_1" {{ old('suara', $member->suara) == 'bass_1' ? 'selected' : '' }}>Bass 1</option>
                            <option value="bass_2" {{ old('suara', $member->suara) == 'bass_2' ? 'selected' : '' }}>Bass 2</option>
                        @endif
                    </select>
                </div>
                <div class="col-6">
                    <label for="positions_id" class="form-label">Jabatan Pengurus</label>
                    <select class="form-select" name="positions_id">
                        <option value="">Tidak Ada</option>
                        @foreach($position as $divisi)
                            @if(!empty($divisi['positions'])) <!-- Check if the division has positions -->
                                <optgroup label="{{ $divisi['nama'] }}">
                                    @foreach($divisi['positions'] as $pos)
                                        <option value="{{ $pos['id'] }}" 
                                            {{ old('positions_id', $member->positions_id ?? '') == $pos['id'] ? 'selected' : '' }}>
                                            {{ $pos['nama'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <button class="w-75 btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    function savePendaftar(value){
            document.getElementById('is_lolos').value = value;
            document.getElementById("lolos-form").submit();
    };

    document.addEventListener("DOMContentLoaded", function(){
        document.getElementById('lembarPenilaian').addEventListener('change', function (event) {
            let file = event.target.files[0];
            let previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Clear previous preview

            if (file) {
                if (file.type.startsWith('image/')) {
                    let img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.width = '150px';
                    img.style.borderRadius = '10px';
                    img.classList.add('mt-2');
                    previewContainer.appendChild(img);
                } else if (file.type === 'application/pdf') {
                    let pdfIcon = document.createElement('div');
                    pdfIcon.innerHTML = `<i class="fas fa-file-pdf fa-3x text-danger"></i>
                                            <p>${file.name}</p>`;
                    previewContainer.appendChild(pdfIcon);
                }
            }
        });
    });
</script>
@endsection