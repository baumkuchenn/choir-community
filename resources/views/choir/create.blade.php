@extends('layouts.header-only')

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ $backUrl ?? route('management.index') }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Buat Komunitas Paduan Suara Baru</h2>
        <form action="{{ route('choir.store') }}" method="POST" class="mb-0">
            @csrf
            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Komunitas Paduan Suara</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="nama_singkatan" class="form-label">Nama Singkatan Komunitas Paduan Suara</label>
                    <input type="text" class="form-control" id="nama_singkatan" name="nama_singkatan" placeholder="" value="{{ old('nama_singkatan') }}" required>
                    <small class="fw-light">Nama ini digunakan untuk pembuatan invoice pada e-ticketing konser</small>
                    @error('nama_singkatan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="logo" class="form-label">Upload Logo</label>
                    <div class="d-flex align-items-center">
                        <img id="logoPreview" src="{{ old('logo') }}" alt="Logo" style="width: 100px; height: 100px;">
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    </div>
                    @error('logo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="kota" class="form-label">Kota Beroperasi</label>
                    <select class="form-select" id="kota" name="kota" required>
                        <option value="">Pilih Kota</option>
                    </select>
                    @error('kota')
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
        // $('#kota').select2({
        //     placeholder: 'Cari Kota...',
        //     allowClear: true,
        //     ajax: {
        //         url: '{{-- route("kota.search") --}}',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function(data) {
        //             return {
        //                 results: data.map(function(item) {
        //                     return { id: item.nama, text: item.nama };
        //                 })
        //             };
        //         }
        //     }
        // });
    });
</script>
@endsection