@extends('layouts.header-only')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow col-11 col-lg-8">
        <div class="card-body">
            <div class="mt-3 mb-3 text-center">
                <h3 class="fw-bold">Masuk ke akunmu</h3>
                <span>Belum punya akun?</span> <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar</a>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>

                <!-- Forgot Password & Submit Button -->
                <div class="d-flex justify-content-between align-items-center">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa password?</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection