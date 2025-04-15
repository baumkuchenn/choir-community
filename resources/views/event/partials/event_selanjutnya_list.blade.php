@if ($eventSelanjutnya->isEmpty())
    <p class="text-center">Komunitas ini belum memiliki kegiatan.</p>
@else
    @foreach ($eventSelanjutnya as $event)
        <div class="card shadow border-0 mb-3">
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-md-7">
                            <h5 class="fw-bold">{{ $event->nama }}</h5>
                            <div class="mt-4">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-calendar-days fa-fw fs-5"></i>
                                    <p class="mb-0">
                                        @php
                                            $mulai = \Carbon\Carbon::parse($event->tanggal_mulai);
                                            $selesai = \Carbon\Carbon::parse($event->tanggal_selesai);
                                        @endphp

                                        @if ($mulai->day === $selesai->day && $mulai->month === $selesai->month && $mulai->year === $selesai->year)
                                            {{ $mulai->translatedFormat('d F Y') }}
                                        @elseif ($mulai->month === $selesai->month && $mulai->year === $selesai->year)
                                            {{ $mulai->translatedFormat('d') }} - {{ $selesai->translatedFormat('d F Y') }}
                                        @else
                                            {{ $mulai->translatedFormat('d F Y') }} - {{ $selesai->translatedFormat('d F Y') }}
                                        @endif
                                    </p>
                                </div>
                                @if ($event->jam_mulai && $event->jam_selesai)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-clock fa-fw fs-5"></i>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($event->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->jam_selesai)->format('H:i') }} WIB</p>
                                    </div>
                                @endif
                                @if ($event->lokasi)
                                    <div class="mt-2 d-flex gap-2">
                                        <i class="fa-solid fa-location-dot fa-fw fs-5"></i>
                                        <p class="mb-0">{{ $event->lokasi }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 text-end d-none d-md-block">
                            <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                            @can('akses-event')
                                <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                            @endcan
                        </div>
                    </div>
                    <div class="col-12 text-end d-block d-md-none">
                        <a href="events/{{ $event->id }}" class="btn btn-primary">Lihat Detail</a>
                        @can('akses-event')
                            <button class="btn btn-outline-danger deleteBtn" data-name="kegiatan {{ $event->nama }}" data-action="{{ route('events.destroy', $event->id) }}">Hapus</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Pagination -->
    {{ $eventSelanjutnya->appends(['search' => request('search')])->links() }}
@endif