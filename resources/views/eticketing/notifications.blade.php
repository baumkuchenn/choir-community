@extends('layouts.eticket')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-center">
        <div class="col-12">
            <h3 class="fw-bold text-center mt-3 mb-3">Notifikasi</h3>
            <div class="card shadow w-100">
                <div class="card-body">
                    <div class="mt-2">
                        @if($notifications->isNotEmpty())
                            @foreach($notifications as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                    $alertClass = $isUnread ? 'alert-info' : 'alert-secondary';
                                @endphp

                                <div class="alert {{ $alertClass }} mb-2">
                                    <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong><br>
                                    <p>{{ $notification->data['message'] ?? '-' }}</p>
                                    <form action="{{ route('eticket.readAndRedirect', $notification->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">{{ $notification->data['button_text']}}</button>
                                    </form>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Tidak ada notifikasi</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection