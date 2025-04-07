@extends('layouts.management')

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-12">
        <h3 class="fw-bold text-center mt-3 mb-3">Notifikasi</h3>
        <div class="card shadow w-100">
            <div class="card-body">
                <div class="mt-2">
                    @if(auth()->user()->notifications->isNotEmpty())
                        @foreach(auth()->user()->notifications->sortByDesc('created_at') as $notification)
                            @php
                                $isUnread = is_null($notification->read_at);
                                $alertClass = $isUnread ? 'alert-info' : 'alert-secondary';
                            @endphp

                            <div class="alert {{ $alertClass }} mb-2">
                                <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong><br>
                                <p>{{ $notification->data['message'] ?? '-' }}</p>
                                @if(isset($notification->data['modal_id']))
                                    <button 
                                        class="btn btn-primary btn-sm open-daftar-modal"
                                        data-id="{{ $notification->id }}"
                                        data-nama="{{ $notification->data['event_nama'] }}"
                                        data-tanggal="{{ $notification->data['event_tanggal'] }}"
                                        data-lokasi="{{ $notification->data['event_lokasi'] }}"
                                        data-action="{{ route('management.event.daftar', ['event' => $notification->data['event_id']]) }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#daftarConfirmModal"
                                    >
                                        {{ $notification->data['button_text']}}
                                    </button>
                                @else
                                    <form action="{{ route('notifications.readAndRedirect', $notification->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">{{ $notification->data['button_text']}}</button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada notifikasi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('management.modal.daftar')
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.open-daftar-modal').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalEventNama').textContent = this.dataset.nama;
                document.getElementById('modalEventTanggal').textContent = this.dataset.tanggal;
                document.getElementById('modalEventLokasi').textContent = this.dataset.lokasi;
                
                const form = document.getElementById('daftarForm');
                form.setAttribute('action', this.dataset.action);

                // AJAX: mark notification as read
                fetch(`management/notifications/read/${this.dataset.id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
            });
        });
    });
</script>
@endsection