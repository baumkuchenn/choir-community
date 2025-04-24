@extends('layouts.eticket')

@section('content')
<div class="ps-3 pe-3 d-flex flex-nowrap">
    <div class="w-100">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-3 border-end">
                <img src="{{ asset('storage/'. $choir->logo) }}" width="96" height="96" style="object-fit: cover;">
                <h5 class="fw-bold">{{ $choir->nama }}</h5>
                <p class="truncate-5-lines small">{!! nl2br(e($choir->deskripsi)) !!}</p>
                <p class="small"><i class="fa-solid fa-location-dot fa-fw fs-5"></i>{{ $choir->alamat }}</p>
            </div>

            <div class="col-lg-9">
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab', 'berlangsung') === 'berlangsung' ? 'active' : '' }}" 
                        href="{{ route('eticket.show-choir', ['id' => $choir->id, 'tab' => 'berlangsung']) }}">
                        Konser Berlangsung
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('tab') === 'lalu' ? 'active' : '' }}" 
                        href="{{ route('eticket.show-choir', ['id' => $choir->id, 'tab' => 'lalu']) }}">
                        Konser Lalu
                        </a>
                    </li>
                </ul>

                <div class="album">
                    <div class="container">
                        @php
                            if ($tab == 'berlangsung'){
                                $events = $konserBerlangsung;
                            } else {
                                $events = $konserLalu;
                            }
                        @endphp

                        <div class="row">
                            @foreach($events as $item)
                                <div class="col-6 col-md-4 col-lg-3 mb-4">
                                    <a href="{{ route('eticket.show', $item->concert->id) }}" class="text-decoration-none">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $item->concert->gambar) }}" class="card-img-top img-fluid" alt="{{ $item->nama }}" style="aspect-ratio: 16/9; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title text-truncate">{{ $item->nama }}</h5>
                                                <p class="card-text mb-0">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                                                <p class="card-text"><b>Rp{{ $item->hargaMulai }}</b></p>
                                                <h6 class="card-title border-top pt-2 text-truncate">{{ $choir->nama }}</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(!$konserBerlangsung->count() && !$konserLalu->count())
                    <p class="text-muted">Penyelenggara ini belum mengadakan konser.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection