@extends('layouts.forum')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    <div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 min-vh-100 border-end" style="width: 20%;">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('forum.index') }}" class="nav-link active">
                    <i class="fas fa-home fa-fw me-2"></i>
                    Home
                </a>
            </li>
            <li class="mt-2">
                <a href="{{ route('forum.create') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-plus-circle me-1"></i>
                    Buat Forum Baru
                </a>
            </li>
            <hr>
            <small>Forum yang diikuti</small>
            @foreach($followForums as $item)
                <li>
                    <a href="{{ route('forum.show', $item->slug) }}" class="nav-link text-body">
                        <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                        {{ $item->nama }}
                    </a>
                </li>
            @endforeach
            <hr>
            <small>Forum populer</small>
            @foreach($topForums as $item)
                <li>
                    <a href="{{ route('forum.show', $item->slug) }}" class="nav-link text-body">
                        <img src="{{ asset('storage/' . $item->foto_profil) }}" alt="Profile" width="32" height="32" class="rounded-circle">
                        {{ $item->nama }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="w-100">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="col-lg-11 mx-auto">
            @can('akses-admin')
                <div class="card shadow-sm">
                    <div class="card-body d-flex">
                        <!-- User Avatar -->
                        <img src="https://github.com/mdo.png" alt="Profile" width="32" height="32" class="rounded-circle">

                        <!-- Post Input Area -->
                        <div class="ps-2 flex-grow-1">
                            <textarea class="form-control border-0" rows="3" placeholder="Buat thread baru sebagai {{ $user->name }}" style="resize: none;"></textarea>

                            <!-- Action buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm"><i class="fa fa-image"></i> Upload foto/video</button>
                                </div>
                                <button class="btn btn-primary btn-md px-4 fw-bold">Unggah</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection