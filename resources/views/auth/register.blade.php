@extends('layouts.header-only')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow col-11 col-lg-8">
        <div class="card-body">
            <div class="mt-3 mb-3 text-center">
                <h3 class="fw-bold">Buat akun baru</h3>
                <span>Sudah punya akun?</span> <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Masuk</a>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection