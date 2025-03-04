<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banks')->insert([
            [
                'nama' => 'Bank Central Asia',
                'nama_singkatan' => 'BCA',
                'logo' => 'https://picsum.photos/id/16/2000/1000',
            ],
            [
                'nama' => 'Bank Mandiri',
                'nama_singkatan' => 'Mandiri',
                'logo' => 'https://picsum.photos/id/17/2000/1000',
            ],
        ]);
    }
}
