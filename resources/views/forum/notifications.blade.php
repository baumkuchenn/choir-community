@extends('layouts.forum')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-12">
        <h3 class="fw-bold text-center mt-3 mb-3">Notifikasi</h3>
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="mt-2">
                    @if($notifications->isNotEmpty())
                        @foreach ($notifications as $notification)
                            @php
                                $isUnread = is_null($notification->read_at);
                                $alertClass = $isUnread ? 'alert-info' : 'alert-secondary';
                            @endphp

                            <a href="{{ route('forum-notification.readAndRedirect', $notification->id) }}" class="text-decoration-none">
                                <div class="alert {{ $alertClass }} mb-2">
                                    <span class="fw-bold">{{ $notification->data['commenter'] }}</span> 
                                    membalas : {{ $notification->data['message'] }} •
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada notifikasi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection