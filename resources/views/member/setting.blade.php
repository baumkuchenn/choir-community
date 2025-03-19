@extends('layouts.management')

@section('content')
<div class="container">
    @if(session('status') === 'success')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Daftar Anggota Komunitas</h2>
        <h5 class="mb-3 fw-bold">Pengaturan</h5>
    </div>
</div>
@endsection