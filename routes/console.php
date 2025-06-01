<?php

use App\Models\Concert;
use App\Models\Feedback;
use App\Notifications\BeriFeedbackNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('purchases:expire', function () {
    $expiredTime = now()->subHours(24);

    DB::table('purchases')
        ->where('status', 'Bayar')
        ->where('waktu_pembelian', '<', $expiredTime)
        ->update(['status' => 'Batal']);

    $this->info('Pembelian expired berhasil diperbarui.');
})->purpose('Expire pembelian setelah 24 jam jika belum dibayar')->everyMinute();

Artisan::command('concerts:notify-feedback', function () {
    $concerts = Concert::with(['event', 'ticketTypes.purchases.user'])
        ->whereHas('event', function ($query) {
            $query->whereRaw("CONCAT(tanggal_selesai, ' ', jam_selesai) < ?", [now()]);
        })
        ->get();

    foreach ($concerts as $concert) {
        foreach ($concert->ticketTypes as $ticketType) {
            foreach ($ticketType->purchases as $purchase) {
                $user = $purchase->user;

                // Skip if user already gave feedback
                if (
                    $user &&
                    !Feedback::where('concerts_id', $concert->id)->where('users_id', $user->id)->exists() &&
                    !DatabaseNotification::where('notifiable_id', $user->id)
                        ->where('type', BeriFeedbackNotification::class)
                        ->where('data->concert_id', $concert->id)
                        ->exists()
                ) {
                    $user->notify(new BeriFeedbackNotification($concert, $purchase));
                }
            }
        }
    }

    $this->info('Pembeli akan diberi notifikasi setelah konser selesai.');
})->purpose('Memberi notifikasi pembeli setelah konser selesai')->everyMinute();
