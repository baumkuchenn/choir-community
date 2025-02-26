<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ticket_types')->insert([
            [
                'nama' => 'Reguler',
                'harga' => 50000,
                'jumlah' => 150,
                'pembelian_terakhir' => '2025-02-14 23:00:00',
                'concerts_id' => 1,
            ],
            [
                'nama' => 'VIP',
                'harga' => 75000,
                'jumlah' => 50,
                'pembelian_terakhir' => '2025-02-14 23:00:00',
                'concerts_id' => 1,
            ],
            [
                'nama' => 'Reguler',
                'harga' => 100000,
                'jumlah' => 200,
                'pembelian_terakhir' => '2025-02-15 23:00:00',
                'concerts_id' => 2,
            ],
            [
                'nama' => 'VIP',
                'harga' => 150000,
                'jumlah' => 100,
                'pembelian_terakhir' => '2025-02-15 23:00:00',
                'concerts_id' => 2,
            ],
            [
                'nama' => 'Reguler',
                'harga' => 150000,
                'jumlah' => 250,
                'pembelian_terakhir' => '2025-02-21 23:00:00',
                'concerts_id' => 3,
            ],
            [
                'nama' => 'VIP',
                'harga' => 250000,
                'jumlah' => 50,
                'pembelian_terakhir' => '2025-02-21 23:00:00',
                'concerts_id' => 3,
            ],
            [
                'nama' => 'Reguler',
                'harga' => 80000,
                'jumlah' => 200,
                'pembelian_terakhir' => '2025-02-22 23:00:00',
                'concerts_id' => 4,
            ],
            [
                'nama' => 'VIP',
                'harga' => 100000,
                'jumlah' => 50,
                'pembelian_terakhir' => '2025-02-22 23:00:00',
                'concerts_id' => 4,
            ],
            [
                'nama' => 'Reguler',
                'harga' => 50000,
                'jumlah' => 200,
                'pembelian_terakhir' => '2025-02-28 23:00:00',
                'concerts_id' => 5,
            ],
            [
                'nama' => 'VIP',
                'harga' => 75000,
                'jumlah' => 50,
                'pembelian_terakhir' => '2025-02-28 23:00:00',
                'concerts_id' => 5,
            ],
        ]);
    }
}
