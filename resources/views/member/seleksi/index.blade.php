@extends('layouts.management')

@section('content')
<div class="container">
    <div class="col-md-11 col-lg-11 mx-auto">
        <h2 class="mb-3 fw-bold text-center">Daftar Seleksi Komunitas</h2>
        <a href="{{ route('events.create') }}" class="btn btn-primary mb-3 fw-bold" >+ Tambah Kegiatan</a>

        <h5 class="mb-3 fw-bold">Kegiatan Kedepannya</h5>
        <hr>
        @if ($eventSelanjutnya->isEmpty())
            <p class="text-center">Komunitas ini belum memiliki kegiatan.</p>
        @else
            @foreach ($eventSelanjutnya as $event)
                <div class="card shadow border-0">
                    <div class="card-body">
                        
                    </div>
                </div>
            @endforeach
        @endif

        <h5 class="mt-4 mb-3 fw-bold">Kegiatan Lalu</h5>
        <hr>
        @if ($eventLalu->isEmpty())
            <p class="text-center">Komunitas ini belum memiliki kegiatan.</p>
        @else
            @foreach ($eventLalu as $event)
                <div class="card shadow border-0 mb-3">
                    <div class="card-body">
                        
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection