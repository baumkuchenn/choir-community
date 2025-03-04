@extends('layouts.header-only')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow col-11 col-lg-8">
        <div class="card-body">
            <div class="mt-3 mb-3">
                <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <form method="POST" action="{{ route('verification.send') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-primary">Kirim Ulang</button>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link">Keluar</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection