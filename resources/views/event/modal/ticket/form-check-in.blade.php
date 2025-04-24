<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                @if($purchase)
                    <h5 class="modal-title" id="checkInModalLabel">Check-In Invoice {{ $purchase->invoice->kode }} : {{ $purchase->user->name }}</h5>
                @elseif($invitation)
                    <h5 class="modal-title" id="checkInModalLabel">Check-In Undangan {{ $invitation->nama }}</h5>
                @endif
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Nama Tiket</th>
                            <th>Nomor Barcode</th>
                            <th>Status Check-In</th>
                            <th>Waktu Check-In</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ticketTableBody">
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_type->nama }}</td>
                                <td>{{ $ticket->barcode_code }}</td>
                                <td>{{ $ticket->check_in === 'ya' ? 'Checked In' : 'belum' }}</td>
                                <td>{{ $ticket->check_in_time ? \Carbon\Carbon::parse($ticket->check_in_time)->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    @if ($ticket->check_in !== 'ya')
                                        <button class="btn btn-primary btn-check-in" data-ticket-id="{{ $ticket->id }}">Check-In</button>
                                    @else
                                        <span class="text-success">Checked In</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
