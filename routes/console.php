<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('purchases:expire', function () {
    $expiredTime = Carbon::now()->subHours(24);

    DB::table('purchases')
        ->where('status', 'Bayar')
        ->where('waktu_pembelian', '<', $expiredTime)
        ->update(['status' => 'Batal']);

    $this->info('Expired purchases have been updated.');
})->purpose('Expire purchases after 24 hours if unpaid')->hourly();
