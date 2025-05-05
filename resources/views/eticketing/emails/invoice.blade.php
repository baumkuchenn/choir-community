<div style="width: 100%; max-width: 800px; margin: 0 auto; color: #212529;">
    <div style="margin-top: 16px;">
        <div style="width: 100%; padding: 8px;">
            <img src="{{ asset('storage/' . $concert->gambar) }}" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
            <div style="border: 1px solid #dee2e6; border-radius: 6px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); padding: 16px;">
                <h4 style="margin: 0 0 16px 0; font-weight: bold;">{{ $event->nama }}</h4>
                <div style="margin-top: 16px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <span style="margin-right: 8px;">📅</span>
                        <p style="margin: 0;">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <span style="margin-right: 8px;">⏰</span>
                        <p style="margin: 0;">Open Gate: {{ \Carbon\Carbon::parse($event->jam_mulai)->format('H:i') }} WIB</p>
                    </div>
                    <div style="display: flex; align-items: flex-start;">
                        <span style="margin-right: 8px;">📍</span>
                        <p style="margin: 0;">{{ $event->lokasi }}</p>
                    </div>
                </div>
                <hr style="margin: 16px 0;">
                <div>
                    @foreach ($choirs as $choir)
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <img src="{{ asset('storage/' . $choir->logo) }}" style="width: 40px; height: 40px; margin-right: 10px;">
                            <div>
                                <p style="margin: 0;">Diselenggarakan oleh</p>
                                <p style="margin: 0; font-weight: bold;">{{ $choir->nama }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 16px;">
        <h4 style="font-weight: bold;">Informasi Pesanan</h4>
        <div style="border: 1px solid #dee2e6; border-radius: 6px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); padding: 16px;">
            <div style="display: flex; flex-wrap: wrap;">
                <div style="width: 50%; padding: 8px;">
                    <p style="margin: 0;">Nama</p>
                    <p style="margin: 0; font-weight: bold;">{{ $user->name }}</p>
                </div>
                <div style="width: 50%; padding: 8px;">
                    <p style="margin: 0;">Kode Invoice</p>
                    <p style="margin: 0; font-weight: bold;">{{ $invoice->kode }}</p>
                </div>
                <div style="width: 50%; padding: 8px;">
                    <p style="margin: 0;">Tanggal Pembelian</p>
                    <p style="margin: 0; font-weight: bold;">{{ $purchase->waktu_pembelian }} WIB</p>
                </div>
                <div style="width: 50%; padding: 8px;">
                    <p style="margin: 0;">Tiket Dibeli</p>
                    @foreach($purchaseDetails as $detail)
                        <p style="margin: 0; font-weight: bold;">{{ $detail->nama }} {{ $detail->pivot->jumlah }} tiket</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 16px;">
        <tr>
            @foreach($tickets as $index => $ticket)
                <td style="padding: 8px; width: 50%; vertical-align: top;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border-radius: 6px; text-align: center;">
                        <tr>
                            <td style="padding: 12px;">
                                <p style="margin: 0; font-weight: bold;">{{ $ticket->ticket_type->nama }} #{{ $ticket->number }}</p>
                                <hr style="margin: 8px 0;">
                                <img src="{{ asset('storage/' . $ticket->barcode_image) }}" alt="Barcode" style="width: 100%; max-width: 100%;">
                                <p style="margin: 0;">{{ $ticket->barcode_code }}</p>
                            </td>
                        </tr>
                    </table>
                </td>

                @if(($index + 1) % 2 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>

    <div style="margin-top: 16px;">
        <h4 style="font-weight: bold;">Syarat & Ketentuan</h4>
        <p>{!! nl2br(e($concert->syarat_ketentuan)) !!}</p>
    </div>
</div>