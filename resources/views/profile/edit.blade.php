@extends('layouts.eticket')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    <div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 border-end" style="width: 20%;">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('eticket.myticket') }}" class="nav-link text-body">
                    <i class="fas fa-ticket fa-fw me-2"></i>
                    Tiket Saya
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="nav-link active">
                    <i class="fas fa-user fa-fw me-2"></i>
                    Profil
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-body">
                    <i class="fas fa-cog fa-fw me-2"></i>
                    Pengaturan
                </a>
            </li>
        </ul>
    </div>
    <div class="ps-3" style="width: 90%;">
        @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="col-md-7 col-lg-11 mx-auto border-bottom border-3">
            <h4 class="mb-3 fw-bold">Profil Kamu</h4>
            <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>
            <form class="mb-3" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{ old('name', $user->name) }}" required>
                        <div class="invalid-feedback">
                            Tolong isi nama lengkap anda.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggalLahir" name="tanggal_lahir" placeholder="" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" required>
                        <div class="invalid-feedback">
                            Tolong isi tanggal lahir anda.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenisKelamin" name="jenis_kelamin" required>
                            <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div class="invalid-feedback">
                            Tolong isi jenis kelamin anda.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="noHP" class="form-label">Nomor Handphone</label>
                        <input type="text" class="form-control" id="noHP" name="no_handphone" placeholder="" value="{{ old('no_handphone', $user->no_handphone) }}">
                        <div class="invalid-feedback">
                            Tolong isi nomor HP anda yang valid.
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@mail.com">
                        <div class="invalid-feedback">
                            Tolong isi email anda yang valid.
                        </div>
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                {{ __('Link verifikasi sudah dikirim ke email anda') }}
                            </p>
                            @else
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('Email anda belum diverifikasi') }}

                                <button form="send-verification" class="btn btn-outline-primary me-2">
                                    {{ __('Klik disini untuk verifikasi email anda') }}
                                </button>
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                    <button class="ms-2 col-lg-3 btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>

        <div class="col-md-7 col-lg-11 mx-auto mt-3">
            <h4 class="mb-3 fw-bold">Ganti Password</h4>
            <form class="mb-3" method="POST" action="{{ route('password.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="update_password_current_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="update_password_current_password" name="current_password" required>
                        <div class="invalid-feedback">
                            Tolong isi password lama anda.
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="update_password_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="update_password_password" name="password" required>
                        <div class="invalid-feedback">
                            Tolong isi password baru anda.
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="update_password_password_confirmation" name="password_confirmation" required>
                        <div class="invalid-feedback">
                            Tolong isi konfirmasi password anda.
                        </div>
                    </div>
                    <button class="ms-2 col-lg-3 btn btn-primary" type="submit">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection