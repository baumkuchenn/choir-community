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
        <div class="col-lg-11 mx-auto">
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request('tab', 'events') === 'events' ? 'active' : '' }}" 
                    href="{{ route('eticket.search', ['search_input' => $keyword, 'tab' => 'events']) }}">
                    Konser
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tab') === 'choirs' ? 'active' : '' }}" 
                    href="{{ route('eticket.search', ['search_input' => $keyword, 'tab' => 'choirs']) }}">
                    Penyelenggara
                    </a>
                </li>
            </ul>
            <h5>Hasil pencarian untuk: "{{ $keyword }}"</h5>
            
            <div class="album">
                <div class="container">
                    @if($tab === 'events')
                        <div class="row">
                            @foreach($events as $item)
                                <div class="col-6 col-md-4 col-lg-3 mb-4">
                                    <a href="{{ route('eticket.show', $item->id) }}" class="text-decoration-none">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $item->concert->gambar) }}" class="card-img-top img-fluid" alt="{{ $item->nama }}" style="aspect-ratio: 16/9; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title text-truncate">{{ $item->nama }}</h5>
                                                <p class="card-text mb-0">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                                                <p class="card-text"><b>Rp{{ $item->hargaMulai }}</b></p>
                                                <h6 class="card-title border-top pt-2 text-truncate">{{ $item->choir->nama }}</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
    
                    @elseif($tab === 'choirs')
                        <div class="row">
                            @foreach($choirs as $item)
                                <div class="col-6 col-md-4 col-lg-2 mb-4 d-flex">
                                    <a href="{{ route('eticket.show-choir', $item->id) }}" class="text-decoration-none w-100">
                                        <div class="card text-center border-0 h-100 p-3">
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ asset('storage/' . $item->logo) }}" class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $item->nama }}">
                                            </div>
                                            <div class="mt-3">
                                                <h6 class="card-title truncate-2-lines">
                                                    {{ $item->nama }}
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @if(!$events->count() && !$choirs->count())
                <p class="text-muted">Tidak ada hasil ditemukan.</p>
            @endif
        </div>
    </div>
</div>
@endsection