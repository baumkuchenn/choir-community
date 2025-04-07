@extends('layouts.management')

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">Kembali</a>
        <h2 class="mb-3 fw-bold text-center">Tambah Seleksi Anggota Baru</h2>
        <form action="{{ route('seleksi.store') }}" method="POST" class="mb-0">
            @csrf
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

            <div class="row mb-3">
                <div class="col-12">
                    <label for="pendaftaran_terakhir" class="form-label">Tanggal Pendaftaran Terakhir</label>
                    <input type="date" class="form-control" id="pendaftaran_terakhir" name="pendaftaran_terakhir" placeholder="" value="{{ old('pendaftaran_terakhir') }}" required>
                    @error('pendaftaran_terakhir')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary w-100 fw-bold mt-2">Simpan Seleksi Baru</button>
        </form>
    </div>
</div>
@endsection