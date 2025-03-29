@extends('layouts.header-only')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow col-11 col-lg-8">
        <div class="card-body">
            <h3 class="fw-bold mt-3 mb-3 text-center">Isi data pribadi akunmu</h3>
            <form method="POST" action="{{ route('personal-info.store') }}">
                @csrf
                <!-- Nama -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ old('name') }}" required autofocus>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No Handphone -->
                <div class="mb-3">
                    <label for="no_handphone" class="form-label">Nomor Handphone</label>
                    <input type="text" class="form-control" id="no_handphone" name="no_handphone" placeholder="" value="{{ old('no_handphone') }}" required autofocus>
                    @error('no_handphone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Asal kota -->
                <div class="mb-3">
                    <label for="kota" class="form-label">Asal Kota</label>
                    <input type="text" class="form-control" id="kota" name="kota" placeholder="" value="{{ old('kota') }}" required autofocus>
                    @error('kota')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="" value="{{ old('alamat') }}" required autofocus>
                    @error('alamat')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="" value="{{ old('tanggal_lahir') }}" required autofocus>
                    @error('tanggal_lahir')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="" value="{{ old('password') }}" required autofocus>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="" value="{{ old('password_confirmation') }}" required autofocus>
                    @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection