<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Anda ke {{ $event->nama }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="text-align: left; padding: 24px 16px; background-color: #f9f9f9;">
            <img src="{{ asset('storage/' . $choir->logo) }}" alt="Logo {{ $choir->nama }}" width="96" height="96" style="border-radius: 50%; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
            <p style="margin: 12px 0 0; font-size: 18px; font-weight: bold; color: #333;">{{ $choir->nama }}</p>
        </div>
        <div style="padding: 30px;">
            <p>Selamat pagi/siang/sore {{ $invite->nama }},</p>
            <p>Sehubungan dengan diadakannya acara {{ $event->nama }}, kami mengundang Bapak/Ibu/Saudara untuk menghadiri acara <strong>{{ $event->nama }}</strong> yang dipersembahkan oleh {{ $choir->nama }}.</p>

            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}<br>
            <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($event->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($event->jam_selesai)->format('H:i') }} WIB<br>
            <strong>Lokasi:</strong> {{ $event->lokasi }}</p>

            <hr style="margin: 20px 0;">

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

            <hr style="margin: 20px 0;">

            <p>Silakan tunjukkan barcode ini (baik dicetak atau dari ponsel Anda) saat memasuki venue acara.</p>
            <p>Demikian undangan ini kami buat. Atas perhatiannya, kami ucapkan terima kasih.</p>

            <p style="margin-top: 40px;">Salam,<br><strong>{{ $choir->nama }}</strong></p>
        </div>
    </div>
</body>
</html>
