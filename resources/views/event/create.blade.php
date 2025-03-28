@extends('layouts.management')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <a href="{{ $backUrl ?? route('events.index') }}" class="btn btn-outline-primary">Kembali</a>
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
                    <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan</label>
                    <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan" required>
                        <option value="" disabled selected>Pilih jenis kegiatan</option>
                        <option value="INTERNAL" {{ old('jenis_kegiatan') == 'INTERNAL' ? 'selected' : '' }}>Internal</option>
                        <option value="EKSTERNAL" {{ old('jenis_kegiatan') == 'EKSTERNAL' ? 'selected' : '' }}>External</option>
                        <option value="KONSER" {{ old('jenis_kegiatan') == 'KONSER' ? 'selected' : '' }}>Konser</option>
                    </select>
                    @error('jenis_kegiatan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="sub_kegiatan_id" class="form-label">Sub Kegiatan</label>
                    <select class="form-select" id="sub_kegiatan_id" name="sub_kegiatan_id">
                        <option value="" disabled selected>Pilih sub kegiatan</option>
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
                <div class="col-6">
                    <label for="tanggal_gladi" class="form-label">Tanggal Gladi</label>
                    <input type="date" class="form-control" id="tanggal_gladi" name="tanggal_gladi" placeholder="" value="{{ old('tanggal_gladi') }}">
                    @error('tanggal_gladi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="jam_gladi" class="form-label">Jam Gladi</label>
                    <input type="time" class="form-control" id="jam_gladi" name="jam_gladi" placeholder="" value="{{ old('jam_gladi') }}">
                    @error('jam_gladi')
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
                    <label for="kegiatan_berulang" class="form-label">Kegiatan Berulang</label>
                    <select class="form-select" id="kegiatan_berulang" name="kegiatan_berulang" required>
                        <!-- <option value="ya" {{ old('kegiatan_berulang') == 'ya' ? 'selected' : '' }}>Ya</option> -->
                        <option value="tidak" {{ old('kegiatan_berulang', 'tidak') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('kegiatan_berulang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="kegiatan_kolaborasi" class="form-label">Kegiatan Kolaborasi</label>
                    <select class="form-select" id="kegiatan_kolaborasi" name="kegiatan_kolaborasi" required>
                        <!-- <option value="ya" {{ old('kegiatan_kolaborasi') == 'ya' ? 'selected' : '' }}>Ya</option> -->
                        <option value="tidak" {{ old('kegiatan_kolaborasi', 'tidak') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('kegiatan_kolaborasi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="peran" class="form-label">Peran dalam Kegiatan</label>
                    <select class="form-select" id="peran" name="peran" required>
                        <option value="penyanyi" {{ old('peran', 'penyanyi') == 'penyanyi' ? 'selected' : '' }}>Penyanyi</option>
                        <option value="panitia" {{ old('peran') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                        <option value="keduanya" {{ old('peran') == 'keduanya' ? 'selected' : '' }}>Penyanyi dan Panitia</option>
                    </select>
                    @error('peran')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="panitia_eksternal" class="form-label">Kegiatan Menggunakan Panitia Eksternal</label>
                    <select class="form-select" id="panitia_eksternal" name="panitia_eksternal" required>
                        <option value="ya" {{ old('panitia_eksternal') == 'ya' ? 'selected' : '' }}>Ya</option>
                        <option value="tidak" {{ old('panitia_eksternal', 'tidak') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('panitia_eksternal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="metode_rekrut_panitia" class="form-label">Metode Rekrut Panitia Internal</label>
                    <select class="form-select" id="metode_rekrut_panitia" name="metode_rekrut_panitia" required>
                        <option value="PILIH" {{ old('metode_rekrut_panitia') == 'PILIH' ? 'selected' : '' }}>Pilih langsung</option>
                        <option value="SELEKSI" {{ old('metode_rekrut_panitia', 'SELEKSI') == 'SELEKSI' ? 'selected' : '' }}>Seleksi</option>
                    </select>
                    @error('metode_rekrut_panitia')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <label for="metode_rekrut_penyanyi" class="form-label">Metode Rekrut Penyanyi</label>
                    <select class="form-select" id="metode_rekrut_penyanyi" name="metode_rekrut_penyanyi" required>
                        <option value="PILIH" {{ old('metode_rekrut_penyanyi') == 'PILIH' ? 'selected' : '' }}>Pilih langsung</option>
                        <option value="SELEKSI" {{ old('metode_rekrut_penyanyi', 'SELEKSI') == 'SELEKSI' ? 'selected' : '' }}>Seleksi</option>
                    </select>
                    @error('metode_rekrut_penyanyi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary w-100 fw-bold mt-2">Simpan Kegiatan Baru</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
    });
</script>
@endsection