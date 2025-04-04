@extends('layouts.management')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-12">
        <h3 class="fw-bold text-center mt-3 mb-3">Notifikasi</h3>
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="mt-2">
                    @if($notifications->isNotEmpty())
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">{{ $notification->pesan }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada notifikasi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection