<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Anda ke {{ $event->nama }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 40px;">
    <div style="max-width: 600px; margin: auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="background-color: #007bff; color: white; padding: 20px;">
            <h2 style="margin: 0;">Anda Diundang!</h2>
            <p style="margin: 0;">Berikut adalah tiket untuk <strong>{{ $event->nama }}</strong></p>
        </div>
        <div style="padding: 30px;">
            <p>Selamat pagi/siang/sore {{ $invite->nama }},</p>
            <p>Sehubungan dengan diadakannya acara {{ $event->nama }}, kami mengundang Bapak/Ibu/Saudara untuk menghadiri acara <strong>{{ $event->nama }}</strong> yang dipersembahkan oleh {{ $choir->nama }}.</p>

            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y') }}<br>
            <strong>Waktu:</strong> {{ $event->jam_mulai }} - {{ $event->jam_selesai }} WIB<br>
            <strong>Lokasi:</strong> {{ $event->lokasi }}</p>

            <hr style="margin: 20px 0;">

            <h4>🎟️ Detail Tiket</h4>
            <div style="margin-top: 16px; display: flex; flex-wrap: wrap; justify-content: space-between;">
                @foreach($tickets as $ticket)
                    <div style="width: 100%; max-width: 200px; margin-bottom: 16px; border: 1px solid #dee2e6; border-radius: 6px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); padding: 16px; text-align: center;">
                        <p style="margin: 0; font-weight: bold;">{{ $ticket->ticket_type->nama }} #{{ $ticket->number }}</p>
                        <hr style="margin: 8px 0;">
                        <img src="{{ asset('storage/' . $ticket->barcode_image) }}" alt="Barcode" style="width: 100%;">
                        <p style="margin: 0;">{{ $ticket->barcode_code }}</p>
                    </div>
                @endforeach
            </div>

            <hr style="margin: 20px 0;">

            <p>Silakan tunjukkan barcode ini (baik dicetak atau dari ponsel Anda) saat memasuki venue acara.</p>
            <p>Demikian undangan ini kami buat. Atas perhatiannya, kami ucapkan terima kasih.</p>

            <p style="margin-top: 40px;">Salam,<br><strong>{{ $choir->nama }}</strong></p>
        </div>
    </div>
</body>
</html>
