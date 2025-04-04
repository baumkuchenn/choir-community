@extends('layouts.management')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-12">
        <h3 class="fw-bold text-center mt-3 mb-3">Kalender Kegiatan</h3>
        <div id="calendar" class="mt-4"></div>
        <div id="event-info"></div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var eventInfoEl = document.getElementById('event-info');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'today prev,next'
            },
            events: "{{ route('calendar.show') }}",
            eventDidMount: function(info) {
                // Clear the event info area only once before adding new events
                if (!info.event._cleared) {
                    eventInfoEl.innerHTML = ''; 
                    info.event._cleared = true;
                }

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
    });
</script>
@endsection