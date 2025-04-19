@php
    $layout = Auth::check() && Auth::user()->members()->exists() ? 'layouts.management' : 'layouts.header-only';
@endphp

@extends($layout)

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<div class="container d-flex justify-content-center">
    @if($choir)
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="mt-3 mb-3 text-center">
                <h3 class="fw-bold">{{ $choir->nama }}</h3>
            </div>
            <div class="d-flex flex-column flex-lg-row gap-3 gap-lg-5 mt-5">
                <div class="card shadow w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold">Notifikasi <span class="badge bg-primary">{{ auth()->user()->unreadNotifications->count() }}</span></h4>
                            <a href="{{ route('management.notification') }}" class="btn btn-outline-primary">Lihat Semua</a>
                        </div>

                        <div class="mt-2">
                            @if(auth()->user()->unreadNotifications->isNotEmpty())
                                @foreach(auth()->user()->unreadNotifications->sortByDesc('created_at') as $notification)
                                    <div class="alert alert-info mb-2">
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
                        @include('management.modal.daftar')
                    </div>
                </div>
                <div class="card shadow w-100">
                    <div class="card-body">
                        <h4 class="fw-bold text-center">Kalender Kegiatan</h4>
                        <div id="calendar" class="mt-4"></div>
                        <div id="event-info"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-11 col-lg-8">
            <div class="mt-3 mb-3 text-center">
                <h3 class="fw-bold">Anda belum tergabung pada komunitas paduan suara</h3>
            </div>
            <div class="d-flex flex-column flex-lg-row gap-2">
                <a href="{{ route('choir.join') }}" class="btn btn-outline-primary w-100 fw-bold">Gabung Komunitas Paduan Suara</a>
                <a href="{{ route('choir.create') }}" class="btn btn-primary w-100 fw-bold">Buat Komunitas Paduan Suara Baru</a>
            </div>
            @if ($panitia->isNotEmpty())
                <div class="mt-3">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary w-100 fw-bold">Kegiatan Sebagai Panitia Eksternal</a>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventInfoEl = document.getElementById('event-info');
        let renderedEventIds = new Set();

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'today prev,next'
            },
            events: "{{ route('management.calendar.show') }}",
            datesSet: function () {
                if (eventInfoEl) eventInfoEl.innerHTML = '';
                renderedEventIds.clear(); // Reset the tracking
            },
            eventDidMount: function(info) {
                const eventId = info.event.id || info.event.title + info.event.startStr;
                if (renderedEventIds.has(eventId)) return; // Prevent duplicate
                renderedEventIds.add(eventId);

                let start = new Date(info.event.start).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                let end = info.event.end
                    ? new Date(info.event.end).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    })
                    : null;

                let endDateText = end && end !== start ? `- ${end}` : '';

                let formatTime = (time) => {
                    return time
                        ? new Date(`1970-01-01T${time}`).toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hourCycle: 'h23'
                        }).replace(':', '.')
                        : '-';
                };

                let jamMulai = formatTime(info.event.extendedProps.jam_mulai);
                let jamSelesai = formatTime(info.event.extendedProps.jam_selesai);
                let waktuText = (jamMulai !== '-' && jamSelesai !== '-') ? `${jamMulai} - ${jamSelesai}` : jamMulai;

                let eventDetails = `
                    <div class="card shadow mt-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1">${start} ${endDateText}</p>
                                <p class="mb-1">${waktuText} WIB</p>
                            </div>
                            <h5 class="fw-bold mb-1">${info.event.title}</h5>
                            <p class="mb-1">${info.event.extendedProps.lokasi || '-'}</p>
                        </div>
                    </div>
                `;

                if (eventInfoEl) {
                    eventInfoEl.innerHTML += eventDetails; // Append event details
                }
            }
        });

        calendar.render();

        //Tombol notifikasi
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