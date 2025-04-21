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
        <div class="col-lg-11 mx-auto position-relative">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $item->foto_banner) }}" alt="Banner" class="w-100 rounded" style="height: 180px; object-fit: cover;">
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-between bg-white p-3 rounded-bottom shadow-sm" style="margin-top: -20px;">
                <div style="position: absolute; top: 140px; left: 20px;">
                    <img src="{{ asset('storage/' . $item->foto_profil) }}"
                        alt="Profile"
                        width="96"
                        height="96"
                        class="rounded-circle border border-white shadow">
                </div>
                <div class="mt-2" style="margin-left: 110px;">
                    <h4 class="mb-0">{{ $forum->nama }}</h4>
                    <small class="text-muted">
                        {{ $forum->visibility_label }} • {{ $forum->members->count() }} anggota
                    </small>
                </div>
                <div class="mt-2">
                    <button class="btn btn-primary">Gabung</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection